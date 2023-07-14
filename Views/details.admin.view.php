<?php

/* @var CMW\Entity\Support\SupportEntity $support */
/* @var CMW\Entity\Support\SupportResponseEntity[] $responses */

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

$title = "Support";
$description = "Support";

?>

<div class="d-flex flex-wrap justify-content-between">
    <h3><i class="fa-solid fa-ticket"></i> <span
            class="m-lg-auto">Gestion du ticket <?= $support->getId() ?></span></h3>
</div>

<a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue("PATH_SUBFOLDER") . "cmw-admin/support/manage" ?>" class="btn btn-primary">
    Voir les demandes de supports
</a>

<div class="row mt-3">
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4>Informations :</h4>
            </div>
            <div class="card-body">
                <p>Auteur: <b><?= $support->getUser()->getPseudo() ?></b></p>
                <p>Date: <b><?= $support->getCreated() ?></b></p>
                <p>Confidentialité: <b><?php if ($support->getIsPublic() === 0):  ?>
                            <i class="text-warning fa-regular fa-eye-slash"></i> Privé
                        <?php else: ?>
                            <i class="text-success fa-regular fa-eye"></i> Publique
                        <?php endif; ?></b></p>
                <p>Status: <b><?= $support->getStatusFormatted() ?></b></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-9">
        <div class="card">
            <div class="card-body">
                <h4>Question :</h4>
                <div class="card-in-card p-2">
                    <?= $support->getQuestion() ?>
                </div>
                <hr>
                <h4>Réponses :</h4>
                <?php foreach ($responses as $response): ?>
                    <div class="card-in-card p-2 mb-4">
                        <p><?= $response->getUser()->getPseudo() ?> <small><?= $response->getIsStaffFormatted() ?></small> :</p>
                        <p><?= $response->getResponse() ?></p>
                        <small><?= $response->getCreated() ?></small>
                    </div>
                <?php endforeach; ?>
                <hr>
                <h4>Répondre :</h4>
                <form method="post">
                    <?php (new SecurityManager())->insertHiddenToken() ?>
                    <textarea name="support_response_content" class="form-control"></textarea>
                    <div class="text-center mt-2">
                        <button type="submit" class="btn btn-primary">Répondre</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <?php if ($support->getStatus() !== "2"): ?>
                    <a class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#close-<?= $support->getId() ?>">
                        <i class="fa-solid fa-lock"></i> Cloturer
                    </a>
                    <div class="modal fade text-left" id="close-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title white" id="myModalLabel160">Cloturer <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                </div>
                                <div class="modal-body text-center">
                                    Vous avez bien travailler
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <a href="<?= $support->getCloseUrl() ?>" class="btn btn-success">
                                        <span class="">Cloturer</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#open-<?= $support->getId() ?>">
                        <i class="fa-solid fa-unlock"></i> Ré ouvrir
                    </a>
                    <div class="modal fade text-left" id="open-<?= $support->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title white" id="myModalLabel160">Ré ouvrir <?= mb_strimwidth($support->getQuestion(), 0, 30, '...') ?></h5>
                                </div>
                                <div class="modal-body text-center">
                                    Vous êtes sûr ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <span class=""><?= LangManager::translate("core.btn.close") ?></span>
                                    </button>
                                    <a href="<?= $support->getReOpenUrl() ?>" class="btn btn-warning">
                                        <span class="">Ré ouvrir</span>
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