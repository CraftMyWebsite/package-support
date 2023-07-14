<?php

namespace CMW\Model\Support;

use CMW\Entity\Support\SupportSettingEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;



/**
 * Class @SupportSettingsModel
 * @package Support
 * @author Zomb
 * @version 0.0.1
 */
class SupportSettingsModel extends AbstractModel
{
    public function getConfig(): ?SupportSettingEntity
    {
        $sql = "SELECT * FROM cmw_support_settings LIMIT 1";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);


        if (!$res->execute()) {
            return null;
        }

        $res = $res->fetch();

        return new SupportSettingEntity(
            $res['support_settings_captcha'],
            $res['support_settings_webhook_new_support'] ?? null,
            $res['support_settings_use_webhook_new_support'],
            $res['support_settings_webhook_new_response'] ?? null,
            $res['support_settings_use_webhook_new_response'],
            $res['support_settings_use_mail'],
            $res['support_settings_admin_mail'] ?? null,
            $res['support_settings_custom_sender_mail'] ?? null,
            $res['support_settings_use_sender_mail'],
            $res['support_settings_object_mail_new'] ?? null,
            $res['support_settings_object_mail_response'] ?? null,
            $res['support_settings_updated']
        );
    }

    public function updateConfig(int $support_settings_captcha,?string $support_settings_webhook_new_support,int $support_settings_use_webhook_new_support,
                                 ?string $support_settings_webhook_new_response,int $support_settings_use_webhook_new_response,int $support_settings_use_mail,
                                 ?string $support_settings_admin_mail,?string $support_settings_custom_sender_mail,?int $support_settings_use_sender_mail,
                                 ?string $support_settings_object_mail_new,?string $support_settings_object_mail_response): ?SupportSettingEntity
    {
        $info = array(
            "support_settings_captcha" => $support_settings_captcha,
            "support_settings_webhook_new_support" => $support_settings_webhook_new_support,
            "support_settings_use_webhook_new_support" => $support_settings_use_webhook_new_support,
            "support_settings_webhook_new_response" => $support_settings_webhook_new_response,
            "support_settings_use_webhook_new_response" => $support_settings_use_webhook_new_response,
            "support_settings_use_mail" => $support_settings_use_mail,
            "support_settings_admin_mail" => $support_settings_admin_mail,
            "support_settings_custom_sender_mail" => $support_settings_custom_sender_mail,
            "support_settings_use_sender_mail" => $support_settings_use_sender_mail,
            "support_settings_object_mail_new" => $support_settings_object_mail_new,
            "support_settings_object_mail_response" => $support_settings_object_mail_response
        );

        $sql = "UPDATE cmw_support_settings SET support_settings_captcha = :support_settings_captcha, support_settings_webhook_new_support = :support_settings_webhook_new_support,
                                support_settings_use_webhook_new_support = :support_settings_use_webhook_new_support, support_settings_webhook_new_response= :support_settings_webhook_new_response,
                                support_settings_use_webhook_new_response= :support_settings_use_webhook_new_response, support_settings_use_mail= :support_settings_use_mail,
                                support_settings_admin_mail= :support_settings_admin_mail,
                                support_settings_custom_sender_mail= :support_settings_custom_sender_mail, support_settings_use_sender_mail= :support_settings_use_sender_mail,
                                support_settings_object_mail_new= :support_settings_object_mail_new, support_settings_object_mail_response= :support_settings_object_mail_response";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getConfig();
        }

        return null;
    }
}
