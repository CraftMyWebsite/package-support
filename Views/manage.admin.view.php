<?php

/* @var CMW\Entity\Support\SupportEntity[] $supports */

use CMW\Manager\Lang\LangManager;
use CMW\Model\Support\SupportResponsesModel;

$title = LangManager::translate("support.title");
$description = LangManager::translate("support.description");

?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-ticket"></i> <span
            class="m-lg-auto"><?= LangManager::translate("support.manage.manageTicket") ?></span></h3>
</div>

<section>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
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
                        <td><?= mb_strimwidth($support->getQuestion(), 0, 50, '...') ?></td>
                        <td><?php if ($support->getIsPublic() === 0):  ?>
                                <i class="text-warning fa-regular fa-eye-slash"></i> <?= LangManager::translate("support.manage.private") ?>
                            <?php else: ?>
                                <i class="text-success fa-regular fa-eye"></i> <?= LangManager::translate("support.manage.public") ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $support->getStatusFormatted() ?></td>
                        <td><?= $support->getCreated() ?></td>
                        <td><?= SupportResponsesModel::getInstance()->countResponses($support->getId()) ?></td>
                        <td class="text-center">
                            <a href="details/<?= $support->getSlug() ?>">
                                <i class="text-primary me-3 fa fa-eye"></i>
                            </a>
                            <?php if ($support->getStatus() !== "2"): ?>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#close-<?= $support->getId() ?>">
                                <i class="text-success fa-solid fa-lock"></i>
                            </a>
                            <div class="modal fade text-left" id="close-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("support.manage.close") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                        </div>
                                        <div class="modal-body text-left">
                                            <?= LangManager::translate("support.manage.goodWork") ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                            </button>
                                            <a href="<?= $support->getCloseUrl() ?>" class="btn btn-success">
                                                <span class=""><?= LangManager::translate("support.manage.close") ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#open-<?= $support->getId() ?>">
                                <i class="text-warning fa-solid fa-unlock"></i>
                            </a>
                            <div class="modal fade text-left" id="open-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("support.manage.reOpen") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                        </div>
                                        <div class="modal-body text-left">
                                            <?= LangManager::translate("support.manage.sur") ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                            </button>
                                            <a href="<?= $support->getReOpenUrl() ?>" class="btn btn-warning">
                                                <span class=""><?= LangManager::translate("support.manage.reOpen") ?></span>
                                            </a>
                                        </div>
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
</section>