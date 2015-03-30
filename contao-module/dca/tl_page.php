<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Thyon Design 2010
 * @author     John Brand <http://www.thyon.com>
 * @package    MyFavicon
 * @license    LGPL
 * @filesource
 */
 
 
/**
 * Extend tl_page
 */

// Palettes
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'addFavicon';
$GLOBALS['TL_DCA']['tl_page']['palettes']['root'] = str_replace('{publish_legend}', '{favicon_legend:hide},addFavicon;{publish_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);


// Subpalettes
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['addFavicon'] = 'favicon,rootFavicon,fbFavicon';


// Fields
$GLOBALS['TL_DCA']['tl_page']['fields']['addFavicon'] = array
(
  'label'							=> &$GLOBALS['TL_LANG']['tl_page']['addFavicon'],
  'exclude'						=> true,
  'inputType'					=> 'checkbox',
  'eval'							=> array('submitOnChange'=>true),
  'sql' 							=> "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_page']['fields']['favicon'] = array
(
  'label'							=> &$GLOBALS['TL_LANG']['tl_page']['favicon'],
  'exclude'						=> true,
  'inputType'					=> 'fileTree',
  'eval'							=> array('extensions'=>'ico,png,gif,jpg,jpeg', 'files'=>true, 'fieldType'=>'radio'),
  'sql'								=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['rootFavicon'] = array
(
  'label'							=> &$GLOBALS['TL_LANG']['tl_page']['rootFavicon'],
  'exclude'						=> true,
  'inputType'					=> 'checkbox',
  'sql'								=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_page']['fields']['fbFavicon'] = array
(
  'label'							=> &$GLOBALS['TL_LANG']['tl_page']['fbFavicon'],
  'exclude'						=> true,
  'default'						=> true,
  'inputType'					=> 'checkbox',
  'sql'								=> "char(1) NOT NULL default ''"
);

?>
