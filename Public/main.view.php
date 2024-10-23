<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Model\Support\SupportResponsesModel;
use CMW\Utils\Website;

/* @var CMW\Entity\Support\SupportEntity[] $publicSupport */
/* @var CMW\Entity\Support\SupportSettingEntity $config */

Website::setTitle('Support');
Website::setDescription('Consultez les réponses de nos experts.');
?>

<?php if (\CMW\Controller\Users\UsersController::isAdminLogged()): ?>
    <div style="background-color: orange; padding: 6px; margin-bottom: 10px">
        <span>Votre thème ne gère pas cette page !</span>
        <br>
        <small>Seuls les administrateurs voient ce message !</small>
    </div>
<?php endif;?>

<a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'support/private' ?>">Voir mes demandes</a>

<h2 style="text-align: center">Support</h2>

<form class="space-y-6" action="" method="post">
    <?php (new SecurityManager())->insertHiddenToken() ?>
    <div class="mb-2">
        <label for="support_question">Votre demande :</label>
        <textarea style="display: block; width: 100%" minlength="20" id="support_question" rows="4" name="support_question" placeholder="Impossible de ..."></textarea>
    </div>
    <div style="display: flex; justify-content: space-between">
        <?php if (!$config->visibilityIsDefinedByCustomer()): ?>
            <div style="display: flex; gap:.3rem">
                <div class="flex items-center h-5">
                    <input id="support_is_public" name="support_is_public" checked type="checkbox" value=""
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50">
                </div>
                <label for="support_is_public" class="ml-2 text-sm font-medium text-gray-900">Question publique</label>
            </div>
        <?php endif; ?>
        <div>
            <button type="submit">Soumettre</button>
        </div>
    </div>
</form>

<?php if ($config->getDefaultVisibility() && $config->visibilityIsDefinedByCustomer() || !$config->visibilityIsDefinedByCustomer()): ?>
<div style="display: flex; flex-wrap: wrap; gap: 1rem;">
    <?php foreach ($publicSupport as $support): ?>
    <div style="flex: 0 0 48%; border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px;">
        <a href="<?= $support->getUrl() ?>"><?= mb_strimwidth($support->getQuestion(), 0, 80, '...') ?></a>
        <hr>
        <p>Statut : <?= $support->getStatusFormatted() ?></p>
        <p>Date : <?= $support->getCreated() ?></p>
        <p>Réponses : <?= SupportResponsesModel::getInstance()->countResponses($support->getId()) ?></p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>