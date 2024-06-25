<?php

/* @var CMW\Entity\Support\SupportEntity[] $supports */

use CMW\Manager\Lang\LangManager;
use CMW\Model\Support\SupportResponsesModel;

$title = LangManager::translate("support.title");
$description = LangManager::translate("support.description");

?>

<h3><i class="fa-solid fa-ticket"></i> <?= LangManager::translate("support.manage.manageTicket") ?></h3>

<div class="card">
    <div class="table-container table-container-striped">
        <table class="table" id="table1">
            <thead>
            <tr>
                <th class="text-center"><?= LangManager::translate("support.manage.author") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.question") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.visibility") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.status") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.date") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.responses") ?></th>
                <th class="text-center"><?= LangManager::translate("support.manage.action") ?></th>
            </tr>
            </thead>
            <tbody class="text-center">
            <?php foreach ($supports as $support) : ?>
                <tr class="<?php if ($support->getStatus()==="0") { echo "h6";} ?>">
                    <td><?= $support->getUser()->getPseudo() ?></td>
                    <td><?= mb_strimwidth($support->getQuestion(), 0, 40, '...') ?></td>
                    <td><?php if ($support->getIsPublic() === 0):  ?>
                            <i class="text-warning fa-regular fa-eye-slash"></i> <?= LangManager::translate("support.manage.private") ?>
                        <?php else: ?>
                            <i class="text-success fa-regular fa-eye"></i> <?= LangManager::translate("support.manage.public") ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $support->getStatusFormatted() ?></td>
                    <td><?= $support->getCreated() ?></td>
                    <td><?= SupportResponsesModel::getInstance()->countResponses($support->getId()) ?></td>
                    <td class="text-center space-x-2">
                        <a href="details/<?= $support->getSlug() ?>">
                            <i class="text-info fa fa-eye"></i>
                        </a>
                        <?php if ($support->getStatus() !== "2"): ?>
                            <button data-modal-toggle="modal-close-<?= $support->getId() ?>" type="button"><i class="text-success fa-solid fa-lock"></i></button>
                            <div id="modal-close-<?= $support->getId() ?>" class="modal-container">
                                <div class="modal">
                                    <div class="modal-header-success">
                                        <h6><?= LangManager::translate("support.manage.close") ?> <?= mb_strimwidth($support->getQuestion(), 0, 25, '...') ?></h6>
                                        <button type="button" data-modal-hide="modal-close-<?= $support->getId() ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("support.manage.goodWork") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="<?= $support->getCloseUrl() ?>" class="btn-success">
                                            <?= LangManager::translate("support.manage.close") ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <button data-modal-toggle="modal-open-<?= $support->getId() ?>" type="button"><i class="text-warning fa-solid fa-unlock"></i></button>
                            <div id="modal-open-<?= $support->getId() ?>" class="modal-container">
                                <div class="modal">
                                    <div class="modal-header-warning">
                                        <h6><?= LangManager::translate("support.manage.reOpen") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h6>
                                        <button type="button" data-modal-hide="modal-open-<?= $support->getId() ?>"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <?= LangManager::translate("support.manage.sur") ?>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="<?= $support->getReOpenUrl() ?>" class="btn-warning">
                                            <span class=""><?= LangManager::translate("support.manage.reOpen") ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>