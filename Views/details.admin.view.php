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

<div class="page-title">
    <h3><i class="fa-solid fa-ticket"></i> <?= LangManager::translate("support.details.manageTicket") ?><?= $support->getId() ?></h3>
    <div>
        <a type="button" href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "cmw-admin/support/manage" ?>" class="btn btn-primary">
            < <?= LangManager::translate("support.details.btnViewSupport") ?>
        </a>
        <?php if ($support->getStatus() !== "2"): ?>
            <button class="btn btn-success" type="button"  data-modal-toggle="modal-close-<?= $support->getId() ?>">
                <i class="fa-solid fa-lock"></i> <?= LangManager::translate("support.details.close") ?>
            </button>
            <div id="modal-close-<?= $support->getId() ?>" class="modal-container">
                <div class="modal">
                    <div class="modal-header-success">
                        <h6><?= LangManager::translate("support.details.close") ?> <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h6>
                        <button type="button" data-modal-hide="modal-close-<?= $support->getId() ?>"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <?= LangManager::translate("support.details.goodWork") ?>
                    </div>
                    <div class="modal-footer">
                        <a href="<?= $support->getCloseUrl() ?>" class="btn-success">
                            <span class=""><?= LangManager::translate("support.details.close") ?></span>
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <button class="btn-warning" data-modal-toggle="modal-open-<?= $support->getId() ?>" type="button"><i class="fa-solid fa-unlock"></i> <?= LangManager::translate("support.details.reOpen") ?></button>
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
    </div>
</div>

<div class="grid-3">
    <div class="card">
        <div class="card-header">
            <h6><?= LangManager::translate("support.details.info") ?></h6>
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
    <div class="col-span-2 card">
        <h6><?= LangManager::translate("support.details.question") ?></h6>
            <p><?= $support->getQuestion() ?></p>
    </div>
</div>

<hr>
<h6><?= LangManager::translate("support.details.responses") ?></h6>

<div class="space-y-2">
        <?php foreach ($responses as $response): ?>
            <div class="card">
                <b><?= $response->getUser()->getPseudo() ?> <?php if ($response->getIsStaffFormatted()): ?><span class="badge-success"><?= $response->getIsStaffFormatted() ?></span><?php endif;?> :</b>
                <p><?= $response->getResponse() ?></p>
                <span class="badge-info"><?= $response->getCreated() ?></span>
            </div>
        <?php endforeach; ?>
</div>

<hr>

<div class="card">
    <h6><?= LangManager::translate("support.details.reply") ?></h6>
    <form method="post">
        <?php (new SecurityManager())->insertHiddenToken() ?>
        <textarea id="support_response_content" name="support_response_content" class="textarea"></textarea>
        <div class="text-center mt-2">
            <button type="submit" class="btn btn-primary"><?= LangManager::translate("support.details.btnReply") ?></button>
        </div>
    </form>
</div>
