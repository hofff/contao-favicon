<?php

$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'hofff_favicon';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace(
	'{publish_legend}',
	'{hofff_favicon_legend:hide},hofff_favicon;{publish_legend}',
	$GLOBALS['TL_DCA']['tl_page']['palettes']['root']
);

$GLOBALS['TL_DCA']['tl_page']['subpalettes']['hofff_favicon']
	= 'hofff_favicon_src,hofff_favicon_addRootAlias,hofff_favicon_facebook';

$GLOBALS['TL_DCA']['tl_page']['fields']['hofff_favicon'] = array(
	'label'				=> &$GLOBALS['TL_LANG']['tl_page']['hofff_favicon'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'eval'				=> array(
		'submitOnChange'	=> true,
	),
	'sql' 				=> "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_page']['fields']['hofff_favicon_src'] = array(
	'label'				=> &$GLOBALS['TL_LANG']['tl_page']['hofff_favicon_src'],
	'exclude'			=> true,
	'inputType'			=> 'fileTree',
	'eval'				=> array(
		'extensions'		=> 'ico,png,gif,jpg,jpeg',
		'files'				=> true,
		'fieldType'			=> 'radio',
	),
	'sql'				=> "binary(16) NULL",
);

$GLOBALS['TL_DCA']['tl_page']['fields']['hofff_favicon_addRootAlias'] = array(
	'label'				=> &$GLOBALS['TL_LANG']['tl_page']['hofff_favicon_addRootAlias'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'sql'				=> "char(1) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_page']['fields']['hofff_favicon_facebook'] = array(
	'label'				=> &$GLOBALS['TL_LANG']['tl_page']['hofff_favicon_facebook'],
	'exclude'			=> true,
	'default'			=> true,
	'inputType'			=> 'checkbox',
	'sql'				=> "char(1) NOT NULL default ''",
);
