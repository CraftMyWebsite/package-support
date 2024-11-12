<?php

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;

/* @var CMW\Entity\Support\SupportSettingEntity $config */

$title = LangManager::translate('support.title');
$description = LangManager::translate('support.description');

?>

<div class="page-title">
    <h3><i class="fa-solid fa-gears"></i> <?= LangManager::translate('support.settings.title') ?></h3>
    <button form="supportSettings" type="submit"
            class="btn btn-primary"><?= LangManager::translate('core.btn.save') ?></button>
</div>

<form id="supportSettings" action="" method="post">
    <?php SecurityManager::getInstance()->insertHiddenToken() ?>
<div class="grid-3">
    <div class="card">
        <div>
            <label class="toggle">
                <h6 class="toggle-label"><?= LangManager::translate('support.settings.mail') ?></h6>
                <input type="checkbox" class="toggle-input" id="support_settings_use_mail" name="support_settings_use_mail" <?= $config->getUseMail() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <label for="support_settings_admin_mail"><?= LangManager::translate('support.settings.adminMail') ?></label>
        <input type="email" id="support_settings_admin_mail" class="input" name="support_settings_admin_mail" placeholder="my@mail.com" value="<?= $config->getAdminMail() ?>">
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= LangManager::translate('support.settings.senderMail') ?></p>
                <input type="checkbox" class="toggle-input" id="support_settings_use_sender_mail" name="support_settings_use_sender_mail" <?= $config->getUseSenderMail() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <input type="email" id="default-input" class="input" name="support_settings_custom_sender_mail" placeholder="noreply@mail.com"
               value="<?= $config->getSenderMail() ?>">
        <label for="support_settings_object_mail_new"><?= LangManager::translate('support.settings.objectNew') ?></label>
        <input type="text" id="support_settings_object_mail_new" class="input" name="support_settings_object_mail_new"
               placeholder="<?= LangManager::translate('support.settings.pholderRequest') ?>" value="<?= $config->getObjectMailNews() ?>">
        <label for="support_settings_object_mail_response"><?= LangManager::translate('support.settings.objectResponses') ?></label>
        <input type="text" id="default-input" class="input" name="support_settings_object_mail_response"
               placeholder="<?= LangManager::translate('support.settings.pholderResponses') ?>" value="<?= $config->getObjectMailResponse() ?>">
    </div>
    <div class="card">
        <h6>Discord</h6>
        <div>
            <label class="toggle">
                <p class="toggle-label">Discord Webhook - <?= LangManager::translate('support.settings.newRequest') ?> :</p>
                <input type="checkbox" class="toggle-input" id="support_settings_use_webhook_new_support" name="support_settings_use_webhook_new_support" <?= $config->getUseWebhookNewSupport() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
            <input type="url" id="support_settings_webhook_new_support" class="input" name="support_settings_webhook_new_support" placeholder="https://discord.com/api/webhooks/" value="<?= $config->getWebhookNewSupport() ?>">
        </div>
        <div>
            <label class="toggle">
                <p class="toggle-label">Discord Webhook - <?= LangManager::translate('support.settings.newResponses') ?> :</p>
                <input type="checkbox" class="toggle-input" id="support_settings_use_webhook_new_response" name="support_settings_use_webhook_new_response" <?= $config->getUseWebhookNewResponse() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
            <input type="text" id="support_settings_webhook_new_response" class="input" name="support_settings_webhook_new_response" placeholder="https://discord.com/api/webhooks/" value="<?= $config->getWebhookNewResponse() ?>">
        </div>
    </div>
    <div class="card">
        <h6><?= LangManager::translate('support.settings.global') ?></h6>
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= LangManager::translate('support.settings.visibility') ?> <i data-bs-toggle="tooltip" title="<?= LangManager::translate('support.settings.visibilityTooltip') ?>" class="fa-sharp fa-solid fa-circle-question"></i></p>
                <input type="checkbox" class="toggle-input" id="support_settings_status_defined_by_customer" name="support_settings_status_defined_by_customer" <?= $config->visibilityIsDefinedByCustomer() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
        <div>
            <label class="toggle">
                <p class="toggle-label"><?= LangManager::translate('support.settings.defaultVisibility') ?> <i data-bs-toggle="tooltip" title="<?= LangManager::translate('support.settings.ifOption') ?> ''<?= LangManager::translate('support.settings.visibility') ?>'' <?= LangManager::translate('support.settings.isActive') ?>" class="fa-sharp fa-solid fa-circle-question"></i></p>
                <input type="checkbox" class="toggle-input" id="support_settings_default_status" name="support_settings_default_status" <?= $config->getDefaultVisibility() ? 'checked' : '' ?>>
                <div class="toggle-slider"></div>
            </label>
        </div>
    </div>
</div>
</form>