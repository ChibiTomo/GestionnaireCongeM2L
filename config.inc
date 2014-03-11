<?php
/**
 * Ceci est le fichier de configuration de la base de donn�es.
 * Il contient toute les variables globales n�cessaires � l'installation
 * automatique d'une base neuve.
 */

/**
 * Active/d�sactive le mode de debug (true/false)
 */
define('DEBUG_MODE', true);


/**
 * Cette variable contient les noms des diff�rents types d'utilisateur.
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
$g_cfg_services = array('Comptabilit�', 'R�servation', 'Ressources humaines', 'Direction');

/**
 * Cette variable contient les noms des diff�rents types de cong�.
 * @var array
 */
$g_cfg_types_conge = array('CP', 'RTT');

/**
 * Cette variable contient les soldes de d�part des diff�rents types de cong�.
 * @var array
 */
$g_cfg_solde_depart = array(22, 3);

/**
 * Cette variable contient les noms des diff�rents �tats des conges.
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
		'label' => 'Accept�',
	),
	array(
		'id' => 3,
		'label' => 'Refus�',
	),
);

/**
 * Ces constantes permettent d'obtenir l'id du status recherch�.
 *
 * /!\ LES VALEURS DOIVENT CORRESPONDRE AUX ID DES STATUS /!\
 */
define('CONGE_STATUS_PENDING', 1); 		// En attente
define('CONGE_STATUS_ACCEPTED', 2); 	// Accept�
define('CONGE_STATUS_REFUSED', 3);		// Refus�

$g_cfg_employee_firstnames = array(
	'Jacque', 'Remi', 'Martin', 'Patrique', 'Lucque', 'Robert',
	'Marie', 'Andr�a', '�l�onore', 'Elza', 'Julie', 'Sylvie',
);

$g_cfg_employee_lastnames = array(
	'Durant', 'Lestas', 'Deconvenue', 'Sanshez', 'Tronca', 'Boulbil',
	'Wagit', 'Becam', 'Worshire', 'Roblot', 'Dutronc', 'Byolo',
);

$g_cfg_employee_pwd = 'toto';
$g_cfg_employee_email = '@m2l.fr';

?>