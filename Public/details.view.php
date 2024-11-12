<?php

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Security\SecurityManager;
use CMW\Utils\Website;

/* @var CMW\Entity\Support\SupportEntity $support */
/* @var CMW\Entity\Support\SupportResponseEntity[] $responses */

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

    <div style="display: flex; justify-content: space-between">
        <a href="<?= Website::getProtocol() . '://' . $_SERVER['SERVER_NAME'] . EnvManager::getInstance()->getValue('PATH_SUBFOLDER') . 'support' ?>">Retourner
            au support</a>
        <?php if ($support->getStatus() !== '2'): ?>
            <a href="<?= $support->getCloseUrl() ?>">Cloturer</a>
        <?php endif; ?>
    </div>


    <div style="border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px; margin-top: 20px">
        <div style="display: flex; justify-content: space-between">
            <p>Auteur : <?= $support->getUser()->getPseudo() ?></p>
            <p>État : <?= $support->getStatusFormatted() ?></p>
        </div>
        <div style="display: flex; justify-content: space-between">
            <p>Visibilité : <?= $support->getIsPublicFormatted() ?></p>
            <p>Date : <?= $support->getCreated() ?></p>
        </div>
        <h4>Demande :</h4>
        <div>
            <?= $support->getQuestion() ?>
        </div>
    </div>


    <h4 style="margin-top: 20px">Réponses :</h4>
<?php foreach ($responses as $response): ?>
    <div style="border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px; margin-top: 10px">
        <div style="display: flex; justify-content: space-between">
            <b><?= $response->getUser()->getPseudo() ?> : </b>
            <?php if ($response->getIsStaff()): ?><small
                style="padding: .3rem; background-color: green; color: white"><?= $response->getIsStaffFormatted() ?></small><?php endif; ?>
        </div>
        <p><?= $response->getResponse() ?></p>
        <p><?= $response->getCreated() ?></p>
    </div>
<?php endforeach; ?>

<?php if ($support->getStatus() !== '2'): ?>
    <form style="border: solid 1px #4b4a4a; border-radius: 5px; padding: 9px; margin-top: 10px" action="" method="post">
        <?php SecurityManager::getInstance()->insertHiddenToken() ?>

        <label for="support_response_content" class="block mb-2 text-sm font-medium text-gray-900">Votre
            réponse :</label>
        <textarea minlength="20" id="support_response_content" name="support_response_content" rows="4"
                  style="display: block; width: 100%"
                  placeholder="Vous pouvez ..."></textarea>
        <div style="display: flex; justify-content: center; margin-top: 15px">
            <button type="submit">
                Envoyé
            </button>
        </div>
    </form>
<?php endif; ?>