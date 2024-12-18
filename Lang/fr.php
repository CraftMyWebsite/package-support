<?php

return [
    'title' => 'Support',
    'description' => 'Apportez un support à vos utilisateurs',
    'details' => [
        'manageTicket' => 'Gestion du ticket ',
        'btnViewSupport' => 'Voir les demandes de supports',
        'info' => 'Informations :',
        'author' => 'Auteur:',
        'date' => 'Date:',
        'visibility' => 'Visibilité:',
        'private' => 'Privé',
        'public' => 'Publique',
        'status' => 'Status:',
        'question' => 'Question :',
        'responses' => 'Réponses :',
        'reply' => 'Répondre :',
        'btnReply' => 'Répondre',
        'close' => 'Cloturer',
        'goodWork' => 'Vous avez bien travailler',
        'reOpen' => 'Ré ouvrir',
        'sur' => 'Vous êtes sûr ?',
    ],
    'manage' => [
        'manageTicket' => 'Gestion des tickets',
        'author' => 'Autheur',
        'question' => 'Question',
        'visibility' => 'Visibilité',
        'status' => 'État',
        'date' => 'Date',
        'responses' => 'Réponses',
        'action' => 'Action',
        'private' => 'Privé',
        'public' => 'Publique',
        'close' => 'Cloturer',
        'goodWork' => 'Vous avez bien travailler',
        'reOpen' => 'Ré ouvrir',
        'sur' => 'Vous êtes sûr ?',
    ],
    'settings' => [
        'title' => 'Paramètres',
        'mail' => 'Mail',
        'adminMail' => 'Mail admin :',
        'senderMail' => "Mail d'envoie :",
        'objectNew' => 'Objet mail nouvelle demande :',
        'pholderRequest' => 'Demande prise en compte',
        'objectResponses' => 'Objet mail nouvelle reponse :',
        'pholderResponses' => 'Nouvelle réponse à votre demande',
        'newRequest' => 'nouvelle demande',
        'newResponses' => 'nouvelle réponse',
        'global' => 'Général',
        'captcha' => 'Activer le captcha',
        'visibility' => 'Definir la visiblité',
        'visibilityTooltip' => 'Les utilisateurs peuvent choisir si leur question est publique ou non',
        'defaultVisibility' => 'Publique par défaut',
        'ifOption' => "Si l'option",
        'isActive' => 'est active vous pouvez choisir si les demandes sont publique ou privé',
    ],
    'entity' => [
        'private' => 'Privé',
        'public' => 'Publique',
        'waitingResponse' => ' Attend une réponse',
        'waitingCustomer' => ' Réponse apporté',
        'closed' => ' Clos',
        'staff' => 'Équipe support',
    ],
    'flash' => [
        'title' => 'Support',
        'confNotDefined' => ' Configuration non définie',
        'replySended' => 'Votre réponse est envoyé !',
        'badMailConf' => "La configuration mail de ce site n'est pas bonne !",
        'confApply' => 'Configuration appliqué',
        'reOpened' => 'Cette demande est à nouveau ouverte',
        'connectBeforeNew' => "Connectez-vous avant d'envoyer une demande",
        'connectBeforeReply' => 'Connectez-vous avant de répondre',
        'requestAdded' => 'Votre demande est prise en compte !',
        'captchaEmpty' => 'Veuillez compléter le captcha',
        'connectBeforePrivate' => 'Connectez-vous avant de consulter vos demandes',
        'connectBeforePrivateThis' => 'Connectez-vous avant de consulter cette demande',
        'notYourPrivate' => "Vous n'avez pas le droit de consulter cette demande",
        'subjectClosed' => 'Ce sujet est clos, vous ne pouvez plus répondre',
        'replySend' => 'Votre réponse est envoyé !',
        'connectBeforeClose' => 'Connectez-vous avant de cloturer cette demande',
        'cantClose' => "Vous n'avez pas le droit de cloturer cette demande",
        'isClose' => 'Cette demande est close',
        'unableToAddStaffResponse' => "Impossible d'envoyer votre réponse.",
        'unableToOpenSupport' => "Impossible d'envoyer votre demande.",
    ],
    'webhook' => [
        'access' => 'Accès : %access%',
    ],
    'permissions' => [
        'support' => [
            'show' => 'Afficher et répondre',
            'settings' => 'Gérer les paramètres',
        ],
    ],
    'menu' => [
        'manage' => 'Gestion',
        'settings' => 'Paramètres',
    ],
];
