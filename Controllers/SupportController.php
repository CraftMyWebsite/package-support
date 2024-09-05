<?php

namespace CMW\Controller\Support;

use CMW\Controller\Core\MailController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;

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
use JetBrains\PhpStorm\NoReturn;


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
    private function support(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $supports = SupportModel::getInstance()->getSupport();

        View::createAdminView('Support', 'manage')
            ->addVariableList(["supports" => $supports])
            ->addStyle("Admin/Resources/Assets/Css/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/simple-datatables.js",
                "Admin/Resources/Vendors/Simple-datatables/config-datatables.js")
            ->view();
    }

    #[Link("/details/:supportSlug", Link::GET, [], "/cmw-admin/support")]
    private function supportDetails(string $supportSlug): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

        if (!$support) {
            Redirect::errorPage(404);
        }

        $responses = SupportResponsesModel::getInstance()->getResponseBySupportId($support->getId());

        View::createAdminView('Support', 'details')
            ->addVariableList(["support" => $support, "responses" => $responses])
            ->view();
    }

    #[NoReturn] #[Link("/details/:supportSlug", Link::POST, [], "/cmw-admin/support")]
    private function supportDetailsPostResponse(string $supportSlug): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $config = SupportSettingsModel::getInstance()->getConfig();

        if ($config === null) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.confNotDefined"),
            );
            Redirect::redirectPreviousRoute();
        }

        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

        if (!$support) {
            Redirect::errorPage(404);
        }

        $user = UsersModel::getCurrentUser();

        if (!$user) {
            Redirect::redirectToHome();
        }

        [$support_response_content] = Utils::filterInput("support_response_content");
        $thisResponse = SupportResponsesModel::getInstance()->addStaffResponse(
            $support->getId(),
            $support_response_content, $user->getId(),
        );

        if (!$thisResponse) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("core.toaster.error"),
                LangManager::translate("support.flash.unableToAddStaffResponse"),
            );
            Redirect::redirectPreviousRoute();
        }

        SupportModel::getInstance()->setSupportStatus($support->getId(), 1);
        Flash::send(
            Alert::SUCCESS,
            LangManager::translate("support.flash.title"),
            LangManager::translate("support.flash.replySended"),
        );

        //TODO : Gérer l'envoie de webhook et de mail lors de réponses staff
        if ($config->getUseWebhookNewResponse()) {
            DiscordWebhook::createWebhook($config->getWebhookNewResponse())
                ->setImageUrl(null)
                ->setTts(false)
                ->setTitle($support->getQuestion())
                ->setTitleLink($support->getUrl())
                ->setDescription(
                    LangManager::translate(
                        "support.webhook.access",
                        [
                            'access' => $support->getIsPublicFormatted(),
                        ],
                    ),
                )
                ->setColor('35AFD9')
                ->setFooterText(Website::getWebsiteName())
                ->setFooterIconUrl(null)
                ->setAuthorName($thisResponse->getUser()->getPseudo())
                ->setAuthorUrl(null)
                ->send();
        }
        if ($config->getUseMail()) {
            if ($config->getObjectMailResponse() && MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable()) {
                if ($config->getSenderMail() && $config->getUseSenderMail()) {
                    MailController::getInstance()->sendMailWithSender(
                        $config->getSenderMail(),
                        Website::getWebsiteName(),
                        $thisResponse->getUser()->getMail(),
                        $config->getObjectMailResponse() . " #" . $support->getId(),
                        "Nouvelle réponse à la demande <b>" . $support->getQuestion() . "</b><br>Vous pouvez la consulter <a href='" . $support->getUrl() . "'>ici</a>.");
                } else {
                    MailController::getInstance()->sendMail(
                        $thisResponse->getUser()->getMail(),
                        $config->getObjectMailResponse(),
                        "Nouvelle réponse à la demande <b>" . $support->getQuestion() . "</b><br>Vous pouvez la consulter <a href='" . $support->getUrl() . "'>ici</a>."
                    );
                }
            } else {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.badMailConf"),
                );
            }
        }
        Redirect::redirectPreviousRoute();
    }

    #[Link("/settings", Link::GET, [], "/cmw-admin/support")]
    private function settingsSupport(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.show");

        $config = SupportSettingsModel::getInstance()->getConfig();

        View::createAdminView('Support', 'settings')
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[NoReturn] #[Link("/settings", Link::POST, [], "/cmw-admin/support")]
    private function settingsSupportPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "support.settings");

        //TODO REWRITE THIS PART
        [$support_settings_webhook_new_support, $support_settings_use_webhook_new_support, $support_settings_webhook_new_response,
            $support_settings_use_webhook_new_response, $support_settings_use_mail, $support_settings_admin_mail, $support_settings_custom_sender_mail,
            $support_settings_use_sender_mail, $support_settings_object_mail_new, $support_settings_object_mail_response, $support_settings_status_defined_by_customer, $support_settings_default_status] =
            Utils::filterInput("support_settings_webhook_new_support", "support_settings_use_webhook_new_support",
                "support_settings_webhook_new_response", "support_settings_use_webhook_new_response", "support_settings_use_mail", "support_settings_admin_mail",
                "support_settings_custom_sender_mail", "support_settings_use_sender_mail", "support_settings_object_mail_new", "support_settings_object_mail_response", "support_settings_status_defined_by_customer", "support_settings_default_status");

        SupportSettingsModel::getInstance()->updateConfig(($support_settings_webhook_new_support === "" ? null : $support_settings_webhook_new_support), $support_settings_use_webhook_new_support === NULL ? 0 : 1,
            ($support_settings_webhook_new_response === "" ? null : $support_settings_webhook_new_response), $support_settings_use_webhook_new_response === NULL ? 0 : 1, $support_settings_use_mail === NULL ? 0 : 1, ($support_settings_admin_mail === "" ? null : $support_settings_admin_mail)
            , ($support_settings_custom_sender_mail === "" ? null : $support_settings_custom_sender_mail), $support_settings_use_sender_mail === NULL ? 0 : 1, ($support_settings_object_mail_new === "" ? null : $support_settings_object_mail_new), ($support_settings_object_mail_response === "" ? null : $support_settings_object_mail_response), $support_settings_status_defined_by_customer === NULL ? 0 : 1, $support_settings_default_status === NULL ? 0 : 1);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"), LangManager::translate("support.flash.confApply"));

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn] #[Link("/open/:supportSlug", Link::GET, [], "/support")]
    private function publicSupportOpen(string $supportSlug): void
    {
        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

        if (!$support) {
            Redirect::errorPage(404);
        }

        SupportModel::getInstance()->setSupportStatus($support->getId(), 0);
        Flash::send(
            Alert::SUCCESS,
            LangManager::translate("support.flash.title"),
            LangManager::translate("support.flash.reOpened"),
        );
        Redirect::redirectPreviousRoute();
    }

    /*------------PUBLIC AREA------------*/

    #[Link("/", Link::GET, [], "/support")]
    private function publicBaseView(): void
    {
        $config = SupportSettingsModel::getInstance()->getConfig();
        $publicSupport = SupportModel::getInstance()->getPublicSupport();

        $view = new View("Support", "main");
        $view->addVariableList(["publicSupport" => $publicSupport, "config" => $config]);
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
        $view->view();
    }

    #[NoReturn] #[Link("/", Link::POST, [], "/support")]
    private function publicSupportPost(): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.connectBeforeNew"),
            );
            Redirect::redirect('login');
        }

        $config = SupportSettingsModel::getInstance()->getConfig();

        if ($config === null) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.confNotDefined"),
            );
            Redirect::redirectPreviousRoute();
        }

        $user = UsersModel::getCurrentUser();

        if (!$user) {
            Redirect::redirectToHome();
        }

        [$support_question, $support_is_public] = Utils::filterInput("support_question", "support_is_public");
        if (!$config->visibilityIsDefinedByCustomer()) {
            $thisSupport = SupportModel::getInstance()->createSupport(
                $user->getId(),
                $support_question,
                $support_is_public === NULL ? 0 : 1,
            );
        } else {
            $thisSupport = SupportModel::getInstance()->createSupport(
                $user->getId(),
                $support_question,
                $config->getDefaultVisibility(),
            );
        }

        if (!$thisSupport) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("core.toaster.error"),
                LangManager::translate("support.flash.unableToOpenSupport"),
            );
            Redirect::redirectPreviousRoute();
        }

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate("support.flash.title"),
            LangManager::translate("support.flash.requestAdded"),
        );


        if ($config->getUseWebhookNewSupport()) {
            DiscordWebhook::createWebhook($config->getWebhookNewSupport())
                ->setImageUrl(null)
                ->setTts(false)
                ->setTitle($thisSupport->getQuestion())
                ->setTitleLink($thisSupport->getUrl())
                ->setDescription(
                    LangManager::translate(
                        "support.webhook.access",
                        [
                            'access' => $thisSupport->getIsPublicFormatted(),
                        ],
                    ),
                )
                ->setColor('3AD935')
                ->setFooterText(Website::getWebsiteName())
                ->setFooterIconUrl(null)
                ->setAuthorName($thisSupport->getUser()->getPseudo())
                ->setAuthorUrl(null)
                ->send();
        }
        if ($config->getUseMail()) {
            if ($config->getObjectMailNews() && MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable()) {
                if ($config->getSenderMail() && $config->getUseSenderMail()) {
                    MailController::getInstance()->sendMailWithSender(
                        $config->getSenderMail(),
                        Website::getWebsiteName(),
                        $thisSupport->getUser()->getMail(),
                        $config->getObjectMailNews() . " #" . $thisSupport->getId(),
                        "Votre demande <b>" . $thisSupport->getQuestion() . "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='" . $thisSupport->getUrl() . "'>ici</a>.");
                } else {
                    MailController::getInstance()->sendMail(
                        $thisSupport->getUser()->getMail(),
                        $config->getObjectMailNews(),
                        "Votre demande <b>" . $thisSupport->getQuestion() . "</b> est bien prise en compte<br>Vous pouvez la consulter <a href='" . $thisSupport->getUrl() . "'>ici</a>.");
                }
            } else {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.badMailConf"),
                );
            }
        }
        Redirect::redirectPreviousRoute();

    }

    #[Link("/private", Link::GET, [], "/support")]
    private function privateBaseView(): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.connectBeforePrivate"),
            );
            Redirect::redirect('login');
        }

        $user = UsersModel::getCurrentUser();

        if (!$user) {
            Redirect::redirectToHome();
        }

        $privateSupport = SupportModel::getInstance()->getPivateSupport($user->getId());

        $view = new View("Support", "private");
        $view->addVariableList(["privateSupport" => $privateSupport]);
        $view->addStyle("Admin/Resources/Vendors/Fontawesome-free/Css/fa-all.min.css");
        $view->view();
    }

    #[Link("/view/:supportSlug", Link::GET, [], "/support")]
    private function publicSupportView(string $supportSlug): void
    {
        $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

        if (!$support) {
            Redirect::errorPage(404);
        }

        if ($support->getIsPublic() === 0) {
            if (!UsersController::isUserLogged()) {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.connectBeforePrivateThis"),
                );
                Redirect::redirect('login');
            } else {
                if ($support->getUser()->getId() !== UsersModel::getCurrentUser()?->getId()) {
                    Flash::send(
                        Alert::ERROR,
                        LangManager::translate("support.flash.title"),
                        LangManager::translate("support.flash.notYourPrivate"),
                    );
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
    private function publicResponsePost(string $supportSlug): void
    {
        $config = SupportSettingsModel::getInstance()->getConfig();

        if ($config === null) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.confNotDefined"),
            );
            Redirect::redirectPreviousRoute();
        }

        if (!UsersController::isUserLogged()) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.connectBeforeReply"),
            );
            Redirect::redirect('login');
        } else {
            $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

            if (!$support) {
                Redirect::errorPage(404);
            }

            if ($support->getStatus() === "2") {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.subjectClosed"),
                );
            } else {
                $user = UsersModel::getCurrentUser();

                if (!$user) {
                    Redirect::redirectToHome();
                }

                [$support_response_content] = Utils::filterInput("support_response_content");
                $thisResponse = SupportResponsesModel::getInstance()->addResponse($support->getId(), $support_response_content, $user->getId());

                if (!$thisResponse) {
                    Flash::send(
                        Alert::ERROR,
                        LangManager::translate("core.toaster.error"),
                        LangManager::translate("support.flash.unableToAddStaffResponse"),
                    );
                    Redirect::redirectPreviousRoute();
                }

                Flash::send(
                    Alert::SUCCESS,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.replySend"),
                );
                if ($config->getUseWebhookNewResponse()) {
                    DiscordWebhook::createWebhook($config->getWebhookNewResponse())
                        ->setImageUrl(null)
                        ->setTts(false)
                        ->setTitle($support->getQuestion())
                        ->setTitleLink($support->getUrl())
                        ->setDescription(
                            LangManager::translate(
                                'support.webhook.access',
                                [
                                    'access' => $support->getIsPublicFormatted(),
                                ]
                            ),
                        )
                        ->setColor('35AFD9')
                        ->setFooterText(Website::getWebsiteName())
                        ->setFooterIconUrl(null)
                        ->setAuthorName($thisResponse->getUser()->getPseudo())
                        ->setAuthorUrl(null)
                        ->send();
                }
                if ($config->getUseMail()) {
                    if ($config->getObjectMailResponse() && MailModel::getInstance()->getConfig() !== null && MailModel::getInstance()->getConfig()->isEnable()) {
                        if ($config->getSenderMail() && $config->getUseSenderMail()) {
                            MailController::getInstance()->sendMailWithSender(
                                $config->getSenderMail(),
                                Website::getWebsiteName(),
                                $thisResponse->getUser()->getMail(),
                                $config->getObjectMailResponse() . " #" . $support->getId(),
                                "Nouvelle réponse à la demande <b>" . $support->getQuestion() . "</b><br>Vous pouvez la consulter <a href='" . $support->getUrl() . "'>ici</a>.");
                        } else {
                            MailController::getInstance()->sendMail(
                                $thisResponse->getUser()->getMail(),
                                $config->getObjectMailResponse(),
                                "Nouvelle réponse à la demande <b>" . $support->getQuestion() . "</b><br>Vous pouvez la consulter <a href='" . $support->getUrl() . "'>ici</a>.");
                        }
                    } else {
                        Flash::send(Alert::ERROR, LangManager::translate("support.flash.title"), LangManager::translate("support.flash.badMailConf"));
                    }
                }
                if ($support->getUser()->getId() === $user->getId()) {
                    SupportModel::getInstance()->setSupportStatus($support->getId(), 0);
                } else {
                    SupportModel::getInstance()->setSupportStatus($support->getId(), 1);
                }
            }
            Redirect::redirectPreviousRoute();
        }
    }

    #[NoReturn] #[Link("/close/:supportSlug", Link::GET, [], "/support")]
    private function publicSupportClose(string $supportSlug): void
    {
        if (!UsersController::isUserLogged()) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate("support.flash.title"),
                LangManager::translate("support.flash.connectBeforeClose"),
            );
            Redirect::redirect('login');
        } else {
            $support = SupportModel::getInstance()->getSupportBySlug($supportSlug);

            if (!$support) {
                Redirect::errorPage(404);
            }

            if ($support->getUser()->getId() !== UsersModel::getCurrentUser()?->getId()) {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.cantClose"),
                );
                Redirect::redirectPreviousRoute();
            } else {
                SupportModel::getInstance()->setSupportStatus($support->getId(), 2);
                Flash::send(
                    Alert::SUCCESS,
                    LangManager::translate("support.flash.title"),
                    LangManager::translate("support.flash.isClose"),
                );
                Redirect::redirectPreviousRoute();
            }
        }
    }
}
