<?php

/**
 * Ce fichier inclue tous les fichiers n�cessaire aux fonctionnement du site.
 * C'est lui qui est � inclure dans les autres pages.
 */

// Fichier contenant l'autoloder de Classe
require_once ROOT_DIR . '/include/autoloader.inc';

// Inclusion des constantes en premier pour le bon fonctionnement du site
require_once ROOT_DIR . '/include/constants.inc';

// Inclusion des fichiers de configuration
require_once CONFIG_FILE;
if (file_exists(MYSQL_CONFIG_FILE)) {
	require_once MYSQL_CONFIG_FILE;
}

// Inclusion des variables globales
require_once ROOT_DIR . '/include/globals.inc';

// Inclusion du reste des fichiers
require_once ROOT_DIR . '/include/misc.inc';
require_once ROOT_DIR . '/include/format.inc';
require_once ROOT_DIR . '/include/core.inc';
require_once ROOT_DIR . '/include/action.inc';
require_once ROOT_DIR . '/include/layout.inc';
require_once ROOT_DIR . '/include/error.inc';
require_once ROOT_DIR . '/include/mail.inc';

?>