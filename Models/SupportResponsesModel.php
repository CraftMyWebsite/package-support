<?php

namespace CMW\Model\Support;

use CMW\Entity\Support\SupportResponseEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Model\Users\UsersModel;


/**
 * Class @SupportResponsesModel
 * @package Support
 * @author Zomb
 * @version 0.0.1
 */
class SupportResponsesModel extends AbstractModel
{
    public function getResponseById(int $support_response_id): ? SupportResponseEntity
    {
        $sql = "SELECT * FROM cmw_support_response WHERE support_response_id = :support_response_id";

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(array("support_response_id" => $support_response_id))) {
            return null;
        }

        $res = $res->fetch();

        $user = (new UsersModel())->getUserById($res["user_id"]);
        $support = (new SupportModel())->getSupportById($res["support_id"]);

        return new SupportResponseEntity(
            $res["support_response_id"],
            $support,
            $res["support_response_content"],
            $user,
            $res["support_response_is_staff"],
            $res["support_response_created"]
        );
    }

    /**
     * @return \CMW\Entity\Support\SupportResponseEntity[]
     */
    public function getResponseBySupportId(int $supportId): array
    {

        $sql = "SELECT support_response_id FROM cmw_support_response WHERE support_id = :support_id";
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(array("support_id" => $supportId))) {
            return array();
        }

        $toReturn = array();

        while ($support = $res->fetch()) {
            $toReturn[] = $this->getResponseById($support["support_response_id"]);
        }

        return $toReturn;

    }

    /**
     * @param int $supportId
     * @return string
     * @desc count number of response in question
     */
    public function countResponses(int $supportId): mixed
    {
        $sql = "SELECT COUNT(support_response_id) as count FROM cmw_support_response WHERE support_id = :supportId";
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(array("supportId" => $supportId))) {
            return 0;
        }

        return $res->fetch(0)['count'];
    }

    public function addResponse(int $support_id, string $support_response_content, int $user_id): ?SupportResponseEntity
    {
        $data = array(
            "support_id" => $support_id,
            "support_response_content" => $support_response_content,
            "user_id" => $user_id
        );

        $sql = "INSERT INTO cmw_support_response(support_id, support_response_content, user_id) VALUES (:support_id, :support_response_content, :user_id)";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($data)) {
            $id = $db->lastInsertId();
            return $this->getResponseById($id);
        }
    }

    public function addStaffResponse(int $support_id, string $support_response_content, int $user_id): ?SupportResponseEntity
    {
        $data = array(
            "support_id" => $support_id,
            "support_response_content" => $support_response_content,
            "user_id" => $user_id
        );

        $sql = "INSERT INTO cmw_support_response(support_id, support_response_content, user_id, support_response_is_staff) VALUES (:support_id, :support_response_content, :user_id, 1)";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($data)) {
            $id = $db->lastInsertId();
            return $this->getResponseById($id);
        }
    }
}
