<?php


use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

/* @var CMW\Entity\Support\SupportSettingEntity $config */

$title = LangManager::translate("support.title");
$description = LangManager::translate("support.description");

?>


<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-gears"></i> <span class="m-lg-auto"><?= LangManager::translate("support.settings.title") ?></span></h3>
    <div class="buttons">
        <button form="supportSettings" type="submit"
                class="btn btn-primary"><?= LangManager::translate("core.btn.save") ?></button>
    </div>
</div>


<form id="supportSettings" action="" method="post">
    <?php (new SecurityManager())->insertHiddenToken() ?>
    <section class="row">

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="support_settings_use_mail" name="support_settings_use_mail" <?= $config->getUseMail() ? 'checked' : '' ?>>
                        <label class="form-check-label" for="support_settings_use_mail"><h6><?= LangManager::translate("support.settings.mail") ?></h6></label>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <h6><?= LangManager::translate("support.settings.adminMail") ?></h6>
                        <div class="form-group">
                            <input class="form-control" type="email" name="support_settings_admin_mail" placeholder="my@mail.com" value="<?= $config->getAdminMail() ?>">
                        </div>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="support_settings_use_sender_mail" name="support_settings_use_sender_mail" <?= $config->getUseSenderMail() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="support_settings_use_sender_mail"><h6><?= LangManager::translate("support.settings.senderMail") ?></h6></label>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="email" name="support_settings_custom_sender_mail" placeholder="noreply@mail.com"
                                   value="<?= $config->getSenderMail() ?>">
                        </div>
                    </div>
                    <div>
                        <h6><?= LangManager::translate("support.settings.objectNew") ?></h6>
                        <div class="form-group">
                            <input class="form-control" type="text" name="support_settings_object_mail_new"
                                   placeholder="<?= LangManager::translate("support.settings.pholderRequest") ?>" value="<?= $config->getObjectMailNews() ?>">
                        </div>
                    </div>
                    <div>
                        <h6><?= LangManager::translate("support.settings.objectResponses") ?></h6>
                        <div class="form-group">
                            <input class="form-control" type="text" name="support_settings_object_mail_response"
                                   placeholder="<?= LangManager::translate("support.settings.pholderResponses") ?>" value="<?= $config->getObjectMailResponse() ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6>Discord</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="support_settings_use_webhook_new_support" name="support_settings_use_webhook_new_support" <?= $config->getUseWebhookNewSupport() ? 'checked' : '' ?>>
                        <label class="form-check-label" for="support_settings_use_webhook_new_support"><h6>Discord Webhook - <?= LangManager::translate("support.settings.newRequest") ?> :</h6></label>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="url" name="support_settings_webhook_new_support" placeholder="https://discord.com/api/webhooks/" value="<?= $config->getWebhookNewSupport() ?>">
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="support_settings_use_webhook_new_response" name="support_settings_use_webhook_new_response" <?= $config->getUseWebhookNewResponse() ? 'checked' : '' ?>>
                        <label class="form-check-label" for="support_settings_use_webhook_new_response"><h6>Discord Webhook - <?= LangManager::translate("support.settings.newResponses") ?> :</h6></label>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="url" name="support_settings_webhook_new_response" placeholder="https://discord.com/api/webhooks/" value="<?= $config->getWebhookNewResponse() ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h6><?= LangManager::translate("support.settings.global") ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="support_settings_captcha" name="support_settings_captcha" <?= $config->getCaptcha() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="support_settings_captcha"><?= LangManager::translate("support.settings.captcha") ?></label>
                        </div>
                        <!--TODO : Gérer ceci :
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="captcha" name="support_settings_captcha" <?= $config->getCaptcha() ? 'checked' : '' ?>>
                            <label class="form-check-label" for="captcha"><?= LangManager::translate("support.settings.visibility") ?>
                                <i data-bs-toggle="tooltip" title="<?= LangManager::translate("support.settings.visibilityTooltip") ?>" class="fa-sharp fa-solid fa-circle-question"></i></label>
                        </div>
                        <div>
                            <h6><?= LangManager::translate("support.settings.defaultVisibility") ?></h6>
                            <select class="form-select" name="style" required>
                                <option value="0" <?php //$style === $currentStyle ? "selected" : "" ?>><?= LangManager::translate("support.settings.private") ?></option>
                                <option value="1" <?php //$style === $currentStyle ? "selected" : "" ?>><?= LangManager::translate("support.settings.public") ?></option>
                            </select>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>