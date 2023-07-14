<?php

namespace CMW\Entity\Support;

use CMW\Controller\Core\CoreController;
use CMW\Entity\Users\UserEntity;
use CMW\Manager\Env\EnvManager;
use CMW\Utils\Website;

class SupportEntity
{
    private int $support_id;
    private string $support_question;
    private UserEntity $user_id;
    private string $support_slug;
    private int $support_is_public;
    private int $support_status;
    private string $support_created;
    private string $support_updated;

    /**
     * @param int $support_id
     * @param string $support_question
     * @param UserEntity $user_id
     * @param string $support_slug
     * @param int $support_is_public
     * @param int $support_status
     * @param string $support_created
     * @param string $support_updated
     */
    public function __construct(int $support_id, string $support_question, UserEntity $user_id, string $support_slug, int $support_is_public, int $support_status, string $support_created, string $support_updated)
    {
        $this->support_id = $support_id;
        $this->support_question = $support_question;
        $this->user_id = $user_id;
        $this->support_slug = $support_slug;
        $this->support_is_public = $support_is_public;
        $this->support_status = $support_status;
        $this->support_created = $support_created;
        $this->support_updated = $support_updated;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->support_id;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->support_question;
    }

    /**
     * @return UserEntity
     */
    public function getUser(): UserEntity
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->support_slug;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Website::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") ."support/view/$this->support_slug";
    }

    /**
     * @return int
     */
    public function getIsPublic(): int
    {
        return $this->support_is_public;
    }

    /**
     * @return string
     */
    public function getIsPublicFormatted(): string
    {
        if ($this->support_is_public === 0) {return "Privé";}
        if ($this->support_is_public === 1) {return "Publique";}
    }

    /**
     * @return int
     */
    public function getStatus(): string
    {
        return $this->support_status;
    }

    /**
     * @return string
     */
    public function getStatusFormatted(): string
    {
        if ($this->support_status === 0) {return "<i class='fa-solid fa-spinner fa-spin' style='color: #1159d4;'></i> Waiting for response";}
        if ($this->support_status === 1) {return "<i class='fa-solid fa-spinner fa-spin-pulse' style='color: #1bbba9;'></i> Waiting for customer";}
        if ($this->support_status === 2) {return "<i class='fa-regular fa-circle-check' style='color: #15d518;'></i> Closed";}
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return CoreController::formatDate($this->support_created);
    }

    /**
     * @return string
     */
    public function getCloseUrl(): string
    {
        return Website::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") ."support/close/$this->support_slug";
    }

    /**
     * @return string
     */
    public function getReOpenUrl(): string
    {
        return Website::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") ."support/open/$this->support_slug";
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return CoreController::formatDate($this->support_updated);
    }

}