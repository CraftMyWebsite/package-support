<?php

namespace CMW\Entity\Support;

use CMW\Controller\Core\CoreController;
use CMW\Entity\Users\UserEntity;

class SupportSettingEntity
{
    private ?string $support_settings_webhook_new_support;
    private bool $support_settings_use_webhook_new_support;
    private ?string $support_settings_webhook_new_response;
    private bool $support_settings_use_webhook_new_response;
    private bool $support_settings_use_mail;
    private ?string $support_settings_admin_mail;
    private ?string $support_settings_custom_sender_mail;
    private bool $support_settings_use_sender_mail;
    private ?string $support_settings_object_mail_new;
    private ?string $support_settings_object_mail_response;
    private bool $support_settings_status_defined_by_customer;
    private bool $support_settings_default_status;
    private string $support_settings_updated;

    /**
     * @param ?string $support_settings_webhook_new_support
     * @param bool $support_settings_use_webhook_new_support
     * @param ?string $support_settings_webhook_new_response
     * @param bool $support_settings_use_webhook_new_response
     * @param bool $support_settings_use_mail
     * @param ?string $support_settings_admin_mail
     * @param ?string $support_settings_custom_sender_mail
     * @param bool $support_settings_use_sender_mail
     * @param ?string $support_settings_object_mail_new
     * @param ?string $support_settings_object_mail_response
     * @param bool $support_settings_status_defined_by_customer
     * @param bool $support_settings_default_status
     * @param string $support_settings_updated
     */
    public function __construct(?string $support_settings_webhook_new_support,bool $support_settings_use_webhook_new_support,
                                ?string $support_settings_webhook_new_response,bool $support_settings_use_webhook_new_response,bool $support_settings_use_mail,
                                ?string $support_settings_admin_mail,?string $support_settings_custom_sender_mail,bool $support_settings_use_sender_mail,
                                ?string $support_settings_object_mail_new,?string $support_settings_object_mail_response,
                                bool $support_settings_status_defined_by_customer,bool $support_settings_default_status,string $support_settings_updated)
    {
        $this->support_settings_webhook_new_support = $support_settings_webhook_new_support;
        $this->support_settings_use_webhook_new_support = $support_settings_use_webhook_new_support;
        $this->support_settings_webhook_new_response = $support_settings_webhook_new_response;
        $this->support_settings_use_webhook_new_response = $support_settings_use_webhook_new_response;
        $this->support_settings_use_mail = $support_settings_use_mail;
        $this->support_settings_admin_mail = $support_settings_admin_mail;
        $this->support_settings_custom_sender_mail = $support_settings_custom_sender_mail;
        $this->support_settings_use_sender_mail = $support_settings_use_sender_mail;
        $this->support_settings_object_mail_new = $support_settings_object_mail_new;
        $this->support_settings_object_mail_response = $support_settings_object_mail_response;
        $this->support_settings_status_defined_by_customer = $support_settings_status_defined_by_customer;
        $this->support_settings_default_status = $support_settings_default_status;
        $this->support_settings_updated = $support_settings_updated;
    }

    /**
     * @return ?string
     */
    public function getWebhookNewSupport(): ?string
    {
        return $this->support_settings_webhook_new_support;
    }

    /**
     * @return bool
     */
    public function getUseWebhookNewSupport(): bool
    {
        return $this->support_settings_use_webhook_new_support;
    }

    /**
     * @return ?string
     */
    public function getWebhookNewResponse(): ?string
    {
        return $this->support_settings_webhook_new_response;
    }

    /**
     * @return bool
     */
    public function getUseWebhookNewResponse(): bool
    {
        return $this->support_settings_use_webhook_new_response;
    }

    /**
     * @return bool
     */
    public function getUseMail(): bool
    {
        return $this->support_settings_use_mail;
    }

    /**
     * @return ?string
     */
    public function getAdminMail(): ?string
    {
        return $this->support_settings_admin_mail;
    }

    /**
     * @return ?string
     */
    public function getSenderMail(): ?string
    {
        return $this->support_settings_custom_sender_mail;
    }

    /**
     * @return bool
     */
    public function getUseSenderMail(): bool
    {
        return $this->support_settings_use_sender_mail;
    }

    /**
     * @return ?string
     */
    public function getObjectMailNews(): ?string
    {
        return $this->support_settings_object_mail_new;
    }

    /**
     * @return ?string
     */
    public function getObjectMailResponse(): ?string
    {
        return $this->support_settings_object_mail_response;
    }

    /**
     * @return bool
     */
    public function visibilityIsDefinedByCustomer(): bool
    {
        return $this->support_settings_status_defined_by_customer;
    }

    /**
     * @return bool
     */
    public function getDefaultVisibility(): bool
    {
        return $this->support_settings_default_status;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return CoreController::formatDate($this->support_settings_updated);
    }

}