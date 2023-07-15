<?php

/* @var CMW\Entity\Support\SupportEntity $support */
/* @var CMW\Entity\Support\SupportResponseEntity[] $responses */

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

$title = LangManager::translate("support.title");
$description = LangManager::translate("support.description");

?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-ticket"></i> <span
            class="m-lg-auto"><?= LangManager::translate("support.details.manageTicket") ?><?= $support->getId() ?></span></h3>
</div>

<a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "cmw-admin/support/manage" ?>" class="btn btn-primary">
    <?= LangManager::translate("support.details.btnViewSupport") ?>
</a>

<div class="row mt-3">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4><?= LangManager::translate("support.details.info") ?></h4>
            </div>
            <div class="card-body">
                <p><?= LangManager::translate("support.details.author") ?> <b><?= $support->getUser()->getPseudo() ?></b></p>
                <p><?= LangManager::translate("support.details.date") ?> <b><?= $support->getCreated() ?></b></p>
                <p><?= LangManager::translate("support.details.visibility") ?> <b><?php if ($support->getIsPublic() === 0):  ?>
                            <i class="text-warning fa-regular fa-eye-slash"></i> <?= LangManager::translate("support.details.private") ?>
                        <?php else: ?>
                            <i class="text-success fa-regular fa-eye"></i> <?= LangManager::translate("support.details.public") ?>
                        <?php endif; ?></b></p>
                <p><?= LangManager::translate("support.details.status") ?> <b><?= $support->getStatusFormatted() ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-9">
        <div class="card">
            <div class="card-body">
                <h4><?= LangManager::translate("support.details.question") ?></h4>
                <div class="card-in-card p-2">
                    <?= $support->getQuestion() ?>
                </div>
                <hr>
                <h4><?= LangManager::translate("support.details.responses") ?></h4>
                <?php foreach ($responses as $response): ?>
                    <div class="card-in-card p-2 mb-4">
                        <p><?= $response->getUser()->getPseudo() ?> <small><?= $response->getIsStaffFormatted() ?></small> :</p>
                        <p><?= $response->getResponse() ?></p>
                        <small><?= $response->getCreated() ?></small>
                    </div>
                <?php endforeach; ?>
                <hr>
                <h4><?= LangManager::translate("support.details.reply") ?></h4>
                <form method="post">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <textarea name="support_response_content" class="form-control"></textarea>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary"><?= LangManager::translate("support.details.btnReply") ?></button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <?php if ($support->getStatus() !== "2"): ?>
                    <a class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#close-<?= $support->getId() ?>">
                        <i class="fa-solid fa-lock"></i> <?= LangManager::translate("support.details.close") ?>
                    </a>
                    <div class="modal fade text-left" id="close-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("support.details.close") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                </div>
                                <div class="modal-body text-center">
                                    <?= LangManager::translate("support.details.goodWork") ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <a href="<?= $support->getCloseUrl() ?>" class="btn btn-success">
                                        <span class=""><?= LangManager::translate("support.details.close") ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#open-<?= $support->getId() ?>">
                        <i class="fa-solid fa-unlock"></i> <?= LangManager::translate("support.details.reOpen") ?>
                    </a>
                    <div class="modal fade text-left" id="open-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title white" id="myModalLabel160"><?= LangManager::translate("support.details.reOpen") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                </div>
                                <div class="modal-body text-center">
                                    <?= LangManager::translate("support.details.sur") ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <a href="<?= $support->getReOpenUrl() ?>" class="btn btn-warning">
                                        <span class=""><?= LangManager::translate("support.details.reOpen") ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>