<?php

namespace CMW\Model\Support;

use CMW\Entity\Support\SupportEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Model\Users\UsersModel;
use CMW\Utils\Utils;

/**
 * Class @SupportModel
 * @package Support
 * @author Zomb
 * @version 0.0.1
 */
class SupportModel extends AbstractModel
{
    public function getSupportById(int $support_id): ?SupportEntity
    {
        $sql = 'SELECT * FROM cmw_support WHERE support_id = :support_id';

        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(['support_id' => $support_id])) {
            return null;
        }

        $res = $res->fetch();

        $user = (new UsersModel())->getUserById($res['user_id']);

        return new SupportEntity(
            $res['support_id'],
            $res['support_question'],
            $user,
            $res['support_slug'],
            $res['support_is_public'],
            $res['support_status'],
            $res['support_created'],
            $res['support_updated']
        );
    }

    /**
     * @return \CMW\Entity\Support\SupportEntity[]
     */
    public function getSupport(): array
    {
        $sql = 'SELECT support_id FROM cmw_support';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($support = $res->fetch()) {
            $toReturn[] = $this->getSupportById($support['support_id']);
        }

        return $toReturn;
    }

    /**
     * @return \CMW\Entity\Support\SupportEntity
     */
    public function getSupportBySlug(string $slug): ?SupportEntity
    {
        $sql = 'SELECT support_id FROM cmw_support WHERE support_slug = :support_slug';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(['support_slug' => $slug])) {
            return null;
        }

        $res = $res->fetch();

        if (!$res) {
            return null;
        }

        return $this->getSupportById($res['support_id']);
    }

    /**
     * @return \CMW\Entity\Support\SupportEntity[]
     */
    public function getPublicSupport(): array
    {
        $sql = 'SELECT support_id FROM cmw_support WHERE support_is_public = 1';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($support = $res->fetch()) {
            $toReturn[] = $this->getSupportById($support['support_id']);
        }

        return $toReturn;
    }

    /**
     * @return \CMW\Entity\Support\SupportEntity[]
     */
    public function getPivateSupport(int $userId): array
    {
        $sql = 'SELECT support_id FROM cmw_support WHERE user_id = :userId';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute(['userId' => $userId])) {
            return [];
        }

        $toReturn = [];

        while ($support = $res->fetch()) {
            $toReturn[] = $this->getSupportById($support['support_id']);
        }

        return $toReturn;
    }

    public function createSupport(int $user_id, string $support_question, int $support_is_public): ?SupportEntity
    {
        $data = [
            'user_id' => $user_id,
            'support_question' => $support_question,
            'support_is_public' => $support_is_public,
            'support_slug' => 'NOT_DEFINED',
            'support_status' => '0',
        ];

        $sql = 'INSERT INTO cmw_support(user_id, support_question, support_is_public, support_slug, support_status) VALUES (:user_id, :support_question, :support_is_public, :support_slug, :support_status)';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($data)) {
            $id = $db->lastInsertId();
            $this->setSupportSlug($id, $support_question);
            return $this->getSupportById($id);
        }
    }

    private function setSupportSlug(int $id, string $support_question): void
    {
        $shortUrl = mb_strimwidth($support_question, 0, 30);
        $slug = $this->generateSupportSlug($id, $shortUrl);

        $data = [
            'support_slug' => $slug,
            'support_id' => $id,
        ];

        $sql = 'UPDATE cmw_support SET support_slug = :support_slug WHERE support_id = :support_id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        $req->execute($data);
    }

    public function generateSupportSlug(int $id, string $support_question): string
    {
        return Utils::normalizeForSlug("$id-" . $support_question);
    }

    public function setSupportStatus(int $supportId, int $status): void
    {
        $data = [
            'support_id' => $supportId,
            'support_status' => $status,
        ];

        $sql = 'UPDATE cmw_support SET support_status = :support_status WHERE support_id = :support_id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        $req->execute($data);
    }
}
