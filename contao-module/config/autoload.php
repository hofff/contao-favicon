<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Myfavicon
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\MyFavicon' => 'system/modules/myfavicon/classes/MyFavicon.php',
	'floIcon'          => 'system/modules/myfavicon/classes/floIcon.php',
));
