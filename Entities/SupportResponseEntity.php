<?php

namespace CMW\Entity\Support;

use CMW\Controller\Core\CoreController;
use CMW\Entity\Users\UserEntity;
use CMW\Model\Support\SupportResponsesModel;

class SupportResponseEntity
{
    private int $support_response_id;
    private SupportEntity $support_id;
    private string $support_response_content;
    private UserEntity $user_id;
    private int $support_response_is_staff;
    private string $support_response_created;

    /**
     * @param int $support_response_id
     * @param SupportEntity $support_id
     * @param string $support_response_content
     * @param UserEntity $user_id
     * @param int $support_response_is_staff
     * @param string $support_response_created
     */
    public function __construct(int $support_response_id, SupportEntity $support_id, string $support_response_content, UserEntity $user_id, int $support_response_is_staff, string $support_response_created)
    {
        $this->support_response_id = $support_response_id;
        $this->support_id = $support_id;
        $this->support_response_content = $support_response_content;
        $this->user_id = $user_id;
        $this->support_response_is_staff = $support_response_is_staff;
        $this->support_response_created = $support_response_created;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->support_response_id;
    }

    /**
     * @return SupportEntity
     */
    public function getSupport(): SupportEntity
    {
        return $this->support_id;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->support_response_content;
    }

    /**
     * @return int
     */
    public function countResponse(): int
    {
        return SupportResponsesModel::getInstance()->countResponses($this->getSupport()->getId());
    }

    /**
     * @return UserEntity
     */
    public function getUser(): UserEntity
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getIsStaff(): int
    {
        return $this->support_response_is_staff;
    }

    /**
     * @return string
     */
    public function getIsStaffFormatted(): string
    {
        if ($this->support_response_is_staff === 0) {return "";}
        if ($this->support_response_is_staff === 1) {return "Équipe support";}
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return CoreController::formatDate($this->support_response_created);
    }

}