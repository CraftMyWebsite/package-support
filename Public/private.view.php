<?php

use CMW\Manager\Env\EnvManager;
use CMW\Model\Support\SupportResponsesModel;
use CMW\Utils\Website;

/* @var CMW\Entity\Support\SupportEntity[] $privateSupport */

Website::setTitle('Support');
Website::setDescription('Consultez les réponses de nos experts.');
?>

<section style="width: 70%;padding-bottom: 6rem;margin: 1rem auto auto;">

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

</section>