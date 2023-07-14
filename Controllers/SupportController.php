<?php

namespace CMW\Controller\Support;

use CMW\Controller\Core\MailController;
use CMW\Controller\Core\SecurityController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Manager\Webhook\DiscordWebhook;
use CMW\Model\Core\MailModel;
use CMW\Model\Support\SupportModel;
use CMW\Model\Support\SupportResponsesModel;
use CMW\Model\Support\SupportSettingsModel;
use CMW\Model\Users\UsersModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use CMW\Utils\Website;


/**
 * Class: @SupportController
 * @package Support
 * @author Zomb
 * @version 0.0.1
 */
class SupportController extends AbstractController
{
    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/support")]
    #[Link("/manage", Link::GET, [], "/cmw-admin/support")]
    public function support(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $supports = SupportModel::getInstance()->getSupport();

        View::createAdminView('Support', 'manage')
            ->addVariableList(["supports" => $supports])
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css", "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js", "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->view();
    }

    #[Link("/details/:supportSlug", Link::GET, [], "/cmw-admin/support")]
    public function supportDetails(Request $request, string $supportSlug): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
        $responses = SupportResponsesModel::getInstance()->getResponseBySupportId($support->getId());

        View::createAdminView('Support', 'details')
            ->addVariableList(["support" => $support, "responses" => $responses])
            ->view();
    }

    #[Link("/details/:supportSlug", Link::POST, [], "/cmw-admin/support")]
    public function supportDetailsPostResponse(Request $request, string $supportSlug): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
        $userId = UsersModel::getCurrentUser()->getId();
        [$support_response_content] = Utils::filterInput("support_response_content");
        SupportResponsesModel::getInstance()->addStaffResponse($support->getId(), $support_response_content, $userId);
        SupportModel::getInstance()->setSupportStatus($support->getId(), 1);
        Flash::send(Alert::SUCCESS, "Support", "Votre réponse est envoyé !");
        Redirect::redirectPreviousRoute();
    }

    #[Link("/settings", Link::GET, [], "/cmw-admin/support")]
    public function settingsSupport(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $config = SupportSettingsModel::getInstance()->getConfig();

        View::createAdminView('Support', 'settings')
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/settings", Link::POST, [], "/cmw-admin/support")]
    public function settingsSupportPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.settings");

        [$support_settings_captcha,$support_settings_webhook_new_support,$support_settings_use_webhook_new_support,$support_settings_webhook_new_response,
            $support_settings_use_webhook_new_response,$support_settings_use_mail,$support_settings_admin_mail,$support_settings_custom_sender_mail,
            $support_settings_use_sender_mail,$support_settings_object_mail_new,$support_settings_object_mail_response] =
            Utils::filterInput("support_settings_captcha","support_settings_webhook_new_support","support_settings_use_webhook_new_support",
                "support_settings_webhook_new_response","support_settings_use_webhook_new_response","support_settings_use_mail","support_settings_admin_mail",
                "support_settings_custom_sender_mail","support_settings_use_sender_mail","support_settings_object_mail_new","support_settings_object_mail_response");

        SupportSettingsModel::getInstance()->updateConfig($support_settings_captcha === NULL ? 0 : 1,($support_settings_webhook_new_support === "" ? null : $support_settings_webhook_new_support),$support_settings_use_webhook_new_support === NULL ? 0 : 1,
            ($support_settings_webhook_new_response === "" ? null : $support_settings_webhook_new_response),$support_settings_use_webhook_new_response === NULL ? 0 : 1,$support_settings_use_mail === NULL ? 0 : 1,($support_settings_admin_mail === "" ? null : $support_settings_admin_mail)
            ,($support_settings_custom_sender_mail === "" ? null : $support_settings_custom_sender_mail),$support_settings_use_sender_mail === NULL ? 0 : 1,($support_settings_object_mail_new === "" ? null : $support_settings_object_mail_new),($support_settings_object_mail_response === "" ? null : $support_settings_object_mail_response));

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"), LangManager::translate("newsletter.flash.apply"));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/open/:supportSlug", Link::GET, [], "/support")]
    public function publicSupportOpen(Request $request, string $supportSlug): void
    {
        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
        SupportModel::getInstance()->setSupportStatus($support->getId(), 0);
        Flash::send(Alert::SUCCESS, "Support", "Cette demande est à nouveau ouverte");
        Redirect::redirectPreviousRoute();
    }

    /*------------PUBLIC AREA------------*/

    #[Link("/", Link::GET, [], "/support")]
    public function publicBaseView(): void
    {
        $publicSupport = SupportModel::getInstance()->getPublicSupport();
        $view = new View("Support", "main");
        $view->addVariableList(["publicSupport" => $publicSupport]);
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
        $view->view();
    }

    #[NoReturn] #[Link("/", Link::POST, [], "/support")]
    private function publicSupportPost(): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(Alert::ERROR, "Support", "Connectez-vous avant d'envoyer une demande'");
            Redirect::redirect('login');
        }

        $config = SupportSettingsModel::getInstance()->getConfig();

        if ($config === null){
            Flash::send(Alert::ERROR, "Support", "Configuration non définie");
            Redirect::redirectPreviousRoute();
        }

        if ($config->getCaptcha()) {
            if (SecurityController::checkCaptcha()) {
                $userId = UsersModel::getCurrentUser()->getId();
                [$support_question, $support_is_public] = Utils::filterInput("support_question", "support_is_public");
                $thisSupport = SupportModel::getInstance()->createSupport($userId, $support_question, $support_is_public === NULL ? 0 : 1);
                Flash::send(Alert::SUCCESS, "Support", "Votre demande est prise en compte !");
                if ($config->getUseWebhookNewSupport()) {
                    DiscordWebhook::createWebhook($config->getWebhookNewSupport())
                        ->setImageUrl(null)
                        ->setTts(false)
                        ->setTitle($thisSupport->getQuestion())
                        ->setTitleLink($thisSupport->getUrl())
                        ->setDescription('Accès : '.$thisSupport->getIsPublicFormatted())
                        ->setColor('3AD935')
                        ->setFooterText(Website::getWebsiteName())
                        ->setFooterIconUrl(null)
                        ->setAuthorName($thisSupport->getUser()->getPseudo())
                        ->setAuthorUrl(null)
                        ->send();
                }
                if ($config->getUseMail()) {
                    if(MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable() && $config->getObjectMailNews()){
                        if ($config->getSenderMail() && $config->getUseSenderMail()) {
                            MailController::getInstance()->sendMailWithSender($config->getSenderMail(),Website::getWebsiteName(),$thisSupport->getUser()->getMail(),$config->getObjectMailNews(),
                                "Votre demande <b>".$thisSupport->getQuestion(). "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='". $thisSupport->getUrl() ."'>ici</a>.");
                        } else {
                            MailController::getInstance()->sendMail($thisSupport->getUser()->getMail(),$config->getObjectMailNews(),
                                "Votre demande <b>".$thisSupport->getQuestion(). "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='". $thisSupport->getUrl() ."'>ici</a>.");
                        }
                    } else {
                        Flash::send(Alert::ERROR,"Support" ,"La configuration mail de ce site n'est pas bonne !");
                    }
                }
                Redirect::redirectPreviousRoute();
            } else {
                Flash::send(Alert::ERROR, "Support", "Veuillez compléter le captcha");
                Redirect::redirectPreviousRoute();
            }
        } else {
            $userId = UsersModel::getCurrentUser()->getId();
            [$support_question, $support_is_public] = Utils::filterInput("support_question", "support_is_public");
            $thisSupport = SupportModel::getInstance()->createSupport($userId, $support_question, $support_is_public === NULL ? 0 : 1);
            Flash::send(Alert::SUCCESS, "Support", "Votre demande est prise en compte !");
            if ($config->getUseWebhookNewSupport()) {
                DiscordWebhook::createWebhook($config->getWebhookNewSupport())
                    ->setImageUrl(null)
                    ->setTts(false)
                    ->setTitle($thisSupport->getQuestion())
                    ->setTitleLink($thisSupport->getUrl())
                    ->setDescription('Accès : '.$thisSupport->getIsPublicFormatted())
                    ->setColor('3AD935')
                    ->setFooterText(Website::getWebsiteName())
                    ->setFooterIconUrl(null)
                    ->setAuthorName($thisSupport->getUser()->getPseudo())
                    ->setAuthorUrl(null)
                    ->send();
            }
            if ($config->getUseMail()) {
                if(MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable() && $config->getObjectMailNews()){
                    if ($config->getSenderMail() && $config->getUseSenderMail()) {
                        MailController::getInstance()->sendMailWithSender($config->getSenderMail(),Website::getWebsiteName(),$thisSupport->getUser()->getMail(),$config->getObjectMailNews(),
                        "Votre demande <b>".$thisSupport->getQuestion(). "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='". $thisSupport->getUrl() ."'>ici</a>.");
                    } else {
                        MailController::getInstance()->sendMail($thisSupport->getUser()->getMail(),$config->getObjectMailNews(),
                            "Votre demande <b>".$thisSupport->getQuestion(). "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='". $thisSupport->getUrl() ."'>ici</a>.");
                    }
                } else {
                    Flash::send(Alert::ERROR,"Support" ,"La configuration mail de ce site n'est pas bonne !");
                }
            }
            Redirect::redirectPreviousRoute();
        }
    }

    #[Link("/private", Link::GET, [], "/support")]
    public function privateBaseView(): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(Alert::ERROR, "Support", "Connectez-vous avant de consulter vos demandes");
            Redirect::redirect('login');
        }

        $privateSupport = SupportModel::getInstance()->getPivateSupport(UsersModel::getCurrentUser()->getId());

        $view = new View("Support", "private");
        $view->addVariableList(["privateSupport" => $privateSupport]);
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
        $view->view();
    }

    #[Link("/view/:supportSlug", Link::GET, [], "/support")]
    public function publicSupportView(Request $request, string $supportSlug): void
    {
        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

        if ($support->getIsPublic() === 0) {
            if (!UsersController::isUserLogged()) {
                Flash::send(Alert::ERROR, "Support", "Connectez-vous avant de consulter cette demande");
                Redirect::redirect('login');
            } else {
                if ($support->getUser()->getId() != UsersModel::getCurrentUser()->getId()) {
                    Flash::send(Alert::ERROR, "Support", "Vous n'avez pas le droit de consulter cette demande");
                    Redirect::redirect('support');
                } else {
                    $responses = SupportResponsesModel::getInstance()->getResponseBySupportId($support->getId());
                    $view = new View("Support", "details");
                    $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
                    $view->addVariableList(["support" => $support, "responses" => $responses]);
                    $view->view();
                }
            }
        } else {
            $responses = SupportResponsesModel::getInstance()->getResponseBySupportId($support->getId());
            $view = new View("Support", "details");
            $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
            $view->addVariableList(["support" => $support, "responses" => $responses]);
            $view->view();
        }
    }

    #[NoReturn] #[Link("/view/:supportSlug", Link::POST, [], "/support")]
    private function publicResponsePost(Request $request, string $supportSlug): void
    {
        $config = SupportSettingsModel::getInstance()->getConfig();

        if ($config === null){
            Flash::send(Alert::ERROR, "Support", "Configuration non définie");
            Redirect::redirectPreviousRoute();
        }

        if (!UsersController::isUserLogged()) {
            Flash::send(Alert::ERROR, "Support", "Connectez-vous avant de répondre");
            Redirect::redirect('login');
        } else {
            if ($config->getCaptcha()) {
                if (SecurityController::checkCaptcha()) {
                    $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
                    if ($support->getStatus() === "2") {
                        Flash::send(Alert::ERROR, "Support", "Ce sujet est clos, vous ne pouvez plus répondre");
                    } else {
                        $userId = UsersModel::getCurrentUser()->getId();
                        [$support_response_content] = Utils::filterInput("support_response_content");
                        $thisResponse = SupportResponsesModel::getInstance()->addResponse($support->getId(), $support_response_content, $userId);
                        Flash::send(Alert::SUCCESS, "Support", "Votre réponse est envoyé !");
                        if ($config->getUseWebhookNewResponse()) {
                            DiscordWebhook::createWebhook($config->getWebhookNewResponse())
                                ->setImageUrl(null)
                                ->setTts(false)
                                ->setTitle($support->getQuestion())
                                ->setTitleLink($support->getUrl())
                                ->setDescription('Accès : ' . $support->getIsPublicFormatted())
                                ->setColor('35AFD9')
                                ->setFooterText(Website::getWebsiteName())
                                ->setFooterIconUrl(null)
                                ->setAuthorName($thisResponse->getUser()->getPseudo())
                                ->setAuthorUrl(null)
                                ->send();
                        }
                        if ($config->getUseMail()) {
                            if(MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable() && $config->getObjectMailResponse()){
                                if ($config->getSenderMail() && $config->getUseSenderMail()) {
                                    MailController::getInstance()->sendMailWithSender($config->getSenderMail(),Website::getWebsiteName(),$thisResponse->getUser()->getMail(),$config->getObjectMailResponse(),
                                        "Nouvelle réponse à la demande <b>".$support->getQuestion(). "</b><br>Vous pouvez la consulter <a href='". $support->getUrl() ."'>ici</a>.");
                                } else {
                                    MailController::getInstance()->sendMail($thisResponse->getUser()->getMail(),$config->getObjectMailResponse(),
                                        "Nouvelle réponse à la demande <b>".$support->getQuestion(). "</b><br>Vous pouvez la consulter <a href='". $support->getUrl() ."'>ici</a>.");
                                }
                            } else {
                                Flash::send(Alert::ERROR,"Support" ,"La configuration mail de ce site n'est pas bonne !");
                            }
                        }
                        if ($support->getUser()->getId() === $userId) {
                            SupportModel::getInstance()->setSupportStatus($support->getId(), 0);
                        } else {
                            SupportModel::getInstance()->setSupportStatus($support->getId(), 1);
                        }
                    }
                } else {
                    Flash::send(Alert::ERROR, "Support", "Veuillez compléter le captcha");
                }
                Redirect::redirectPreviousRoute();
            } else {
                $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
                if ($support->getStatus() === "2") {
                    Flash::send(Alert::ERROR, "Support", "Ce sujet est clos, vous ne pouvez plus répondre");
                } else {
                    $userId = UsersModel::getCurrentUser()->getId();
                    [$support_response_content] = Utils::filterInput("support_response_content");
                    $thisResponse = SupportResponsesModel::getInstance()->addResponse($support->getId(), $support_response_content, $userId);
                    Flash::send(Alert::SUCCESS, "Support", "Votre réponse est envoyé !");
                    if ($config->getUseWebhookNewResponse()) {
                        DiscordWebhook::createWebhook($config->getWebhookNewResponse())
                            ->setImageUrl(null)
                            ->setTts(false)
                            ->setTitle($support->getQuestion())
                            ->setTitleLink($support->getUrl())
                            ->setDescription('Accès : ' . $support->getIsPublicFormatted())
                            ->setColor('35AFD9')
                            ->setFooterText(Website::getWebsiteName())
                            ->setFooterIconUrl(null)
                            ->setAuthorName($thisResponse->getUser()->getPseudo())
                            ->setAuthorUrl(null)
                            ->send();
                    }
                    if ($config->getUseMail()) {
                        if(MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable() && $config->getObjectMailResponse()){
                            if ($config->getSenderMail() && $config->getUseSenderMail()) {
                                MailController::getInstance()->sendMailWithSender($config->getSenderMail(),Website::getWebsiteName(),$thisResponse->getUser()->getMail(),$config->getObjectMailResponse(),
                                    "Nouvelle réponse à la demande <b>".$support->getQuestion(). "</b><br>Vous pouvez la consulter <a href='". $support->getUrl() ."'>ici</a>.");
                            } else {
                                MailController::getInstance()->sendMail($thisResponse->getUser()->getMail(),$config->getObjectMailResponse(),
                                    "Nouvelle réponse à la demande <b>".$support->getQuestion(). "</b><br>Vous pouvez la consulter <a href='". $support->getUrl() ."'>ici</a>.");
                            }
                        } else {
                            Flash::send(Alert::ERROR,"Support" ,"La configuration mail de ce site n'est pas bonne !");
                        }
                    }
                    if ($support->getUser()->getId() === $userId) {
                        SupportModel::getInstance()->setSupportStatus($support->getId(), 0);
                    } else {
                        SupportModel::getInstance()->setSupportStatus($support->getId(), 1);
                    }
                }
                Redirect::redirectPreviousRoute();
            }
        }
    }

    #[Link("/close/:supportSlug", Link::GET, [], "/support")]
    public function publicSupportClose(Request $request, string $supportSlug): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(Alert::ERROR, "Support", "Connectez-vous avant de cloturer cette demande");
            Redirect::redirect('login');
        } else {
            $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);
            if ($support->getUser()->getId() != UsersModel::getCurrentUser()->getId()) {
                Flash::send(Alert::ERROR, "Support", "Vous n'avez pas le droit de cloturer cette demande");
                Redirect::redirectPreviousRoute();
            } else {
                SupportModel::getInstance()->setSupportStatus($support->getId(), 2);
                Flash::send(Alert::SUCCESS, "Support", "Cette demande est close");
                Redirect::redirectPreviousRoute();
            }
        }
    }

}
