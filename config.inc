<?php
/**
 * Ceci est le fichier de configuration de la base de données.
 * Il contient toute les variables globales nécessaires à l'installation
 * automatique d'une base neuve.
 */

/**
 * Active/désactive le mode de debug (true/false)
 */
define('DEBUG_MODE', true);

/**
 * Adresse email de provenance des mails
 */
define('CONTACT_MAIL', 'noreply@m2l.fr');

/**
 * Cette variable contient les noms des différents types d'utilisateur.
 *
 * /!\ LES ID DOIVENT CORRESPONDRE A LA VALEUR DES CONSTANTES /!\
 * @var array
 */
$g_cfg_user_type = array(
	array(
		'id' => 1,
		'label' => 'Admin',
	),
	array(
		'id' => 2,
		'label' => 'Normal',
	),
);

/**
 * Ces constantes permettent d'obtenir l'id du type d'utilisateur recherch�.
 *
 * /!\ LES VALEURS DOIVENT CORRESPONDRE AUX ID DES TYPES D'UTILISATEUR /!\
 */
define('USER_TYPE_ADMIN', 1); 	// Admin
define('USER_TYPE_NORMAL', 2); 	// Normal

/**
 * Cette variable contient les noms des diff�rents services.
 * @var array
 */
$g_cfg_services = array('Comptabilité', 'Réservation', 'Ressources humaines', 'Direction');

/**
 * Cette variable contient les noms des différents types de congé.
 * @var array
 */
$g_cfg_types_conge = array('CP', 'RTT');

/**
 * Cette variable contient les soldes de départ des différents types de congé.
 * @var array
 */
$g_cfg_solde_depart = array(22, 3);

/**
 * Cette variable contient les noms des différents états des conges.
 *
 * /!\ LES ID DOIVENT CORRESPONDRE A LA VALEUR DES CONSTANTES /!\
 * @var array
 */
$g_cfg_status_conge = array(
	array(
		'id' => 1,
		'label' => 'En attente',
	),
	array(
		'id' => 2,
		'label' => 'Acceptée',
	),
	array(
		'id' => 3,
		'label' => 'Refusée',
	),
);

/**
 * Ces constantes permettent d'obtenir l'id du status recherché.
 *
 * /!\ LES VALEURS DOIVENT CORRESPONDRE AUX ID DES STATUS /!\
 */
define('CONGE_STATUS_PENDING', 1); 		// En attente
define('CONGE_STATUS_ACCEPTED', 2); 	// Acceptée
define('CONGE_STATUS_REFUSED', 3);		// Refusée

$g_cfg_employee_firstnames = array(
	'Jacque', 'Remi', 'Martin', 'Patrique', 'Lucque', 'Robert',
	'Marie', 'Andréa', 'Éléonore', 'Elza', 'Julie', 'Sylvie',
);

$g_cfg_employee_lastnames = array(
	'Durant', 'Lestas', 'Deconvenue', 'Sanshez', 'Tronca', 'Boulbil',
	'Wagit', 'Becam', 'Worshire', 'Roblot', 'Dutronc', 'Byolo',
);

$g_cfg_employee_pwd = 'toto';
$g_cfg_employee_email = '@m2l.fr';

?>