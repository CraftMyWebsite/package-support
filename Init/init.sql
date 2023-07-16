CREATE TABLE IF NOT EXISTS `cmw_support`
(
    support_id       INT          NOT NULL AUTO_INCREMENT,
    support_question TEXT         NOT NULL,
    user_id          INT          NULL,
    support_slug             VARCHAR(255)        NOT NULL,
    support_is_public         INT NOT NULL DEFAULT 0,
    support_status         INT NOT NULL DEFAULT 0,
    support_created     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    support_updated     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`support_id`),
    CONSTRAINT fk_support_user_id
        FOREIGN KEY (user_id) REFERENCES cmw_users (user_id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cmw_support_response`
(
    support_response_id       INT          NOT NULL AUTO_INCREMENT,
    support_id          INT NOT NULL ,
    support_response_content TEXT         NOT NULL,
    user_id          INT          NULL,
    support_response_is_staff INT NOT NULL DEFAULT 0,
    support_response_created     TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`support_response_id`),
    CONSTRAINT fk_support_response_user_id
        FOREIGN KEY (user_id) REFERENCES cmw_users (user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_support_response_support_id
        FOREIGN KEY (support_id) REFERENCES cmw_support (support_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS cmw_support_settings
(
    support_settings_captcha   INT NOT NULL DEFAULT 0,
    support_settings_webhook_new_support    VARCHAR(255) NULL,
    support_settings_use_webhook_new_support   INT NOT NULL DEFAULT 0,
    support_settings_webhook_new_response    VARCHAR(255) NULL,
    support_settings_use_webhook_new_response   INT NOT NULL DEFAULT 0,
    support_settings_use_mail   INT NOT NULL DEFAULT 0,
    support_settings_admin_mail    VARCHAR(255) NULL,
    support_settings_custom_sender_mail    VARCHAR(255) NULL,
    support_settings_use_sender_mail   INT NOT NULL DEFAULT 0,
    support_settings_object_mail_new    VARCHAR(255) NULL,
    support_settings_object_mail_response    VARCHAR(255) NULL,
    support_settings_status_defined_by_customer   INT NOT NULL DEFAULT 0,
    support_settings_default_status   INT NOT NULL DEFAULT 0,
    support_settings_updated TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
