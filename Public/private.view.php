<?php

use CMW\Manager\Env\EnvManager;
use CMW\Model\Support\SupportResponsesModel;
use CMW\Utils\Website;

/* @var CMW\Entity\Support\SupportEntity[] $privateSupport */

Website::setTitle('Support');
Website::setDescription('Consultez les réponses de nos experts.');
?>

<?php if (\CMW\Controller\Users\UsersController::isAdminLogged()): ?>
    <div style="background-color: orange; padding: 6px; margin-bottom: 10px">
        <span>Votre thème ne gère pas cette page !</span>
        <br>
        <small>Seuls les administrateurs voient ce message !</small>
    </div>
<?php endif; ?>

<a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'support' ?>">Retourner
    au support</a>

<div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 20px">
    <?php foreach ($privateSupport as $support): ?>
        <div style="flex: 0 0 48%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px;">
            <a href="<?= $support->getUrl() ?>"><?= mb_strimwidth($support->getQuestion(), 0, 80, '...') ?></a>
            <hr>
            <p>Statut : <?= $support->getStatusFormatted() ?></p>
            <p>Date : <?= $support->getCreated() ?></p>
            <p>Réponses : <?= SupportResponsesModel::getInstance()->countResponses($support->getId()) ?></p>
        </div>
    <?php endforeach; ?>
</div>
