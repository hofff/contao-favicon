<?php 
/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Core
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Class Frontend
 *
 * Provide methods to add favicon to the page output
 * @copyright  Thyon Design 2010
 * @author     John Brand <http://www.thyon.com>
 * @package    MyFavicon
 * @license    LGPL
 * @filesource
 */


/*
XHTML:
<link rel="icon" type="image/vnd.microsoft.icon" href="/somepath/image.ico" />
<link rel="icon" type="image/png" href="/somepath/image.png" />
<link rel="icon" type="image/gif" href="/somepath/image.gif" />

APPLE:
<link rel="apple-touch-icon" href="/somepath/image.ico" />
<link rel="apple-touch-icon" href="/somepath/image.png" />
<link rel="apple-touch-icon" href="/somepath/image.gif" />
*/


/**
 * Class MyFavicon
 *
 * Provide methods create multi-rez ICO file from PNG, JPG, GIF or link ICO files directly
 * @copyright  Thyon Design 2010
 * @author     John Brand <http://www.thyon.com>
 * @package    Controller
 */
class MyFavicon extends \Frontend
{

	/**
	 * Link or create ICO file
	 * @param object
	 * @param object
	 * @param object
	 */

  public function customFavicon(PageModel $objPage, LayoutModel $objLayout, PageRegular $objPageRegular)
  {

		// Get page info
    $objRootPage = $this->getPageDetails($objPage->rootId);

		if ($objRootPage->addFavicon)
		{
			$objFile = \FilesModel::findByPk($objRootPage->favicon);
	
			if ($objFile !== null && is_file(TL_ROOT . '/' . $objFile->path))
			{
				// make favicon
				$favicon = $this->createIcon($objFile->path, ($objRootPage->rootFavicon ? $objRootPage->alias : ''), $objRootPage->fbFavicon);
			}
		}
  }


	/**
	 * Link or create ICO file
	 * @param object
	 * @param object
	 * @param object
	 */


  public function createIcon($favicon, $root='', $fbicon = false, $location='')
  {
		$this->Import('floIcon');

		// default sizes for icon set
		$arrIconSizes = array(16, 24, 32, 64, 128, 256, 512);

		// default location and filename for favicon
		$myfavicon = $location . 'favicon' . (strlen($root) ? '-'.$root : '') . '.ico';

		// open original file, to see if its and image
    $objFile = new \File($favicon);

		//get image size
		$imgSize = getimagesize(TL_ROOT . '/' . $favicon);

		// create a new icon, if it doesn't exist
		if (!file_exists(TL_ROOT . '/'. $myfavicon))
		{

			// if icon exists, then read it and assign
			if (pathinfo(TL_ROOT . '/' . $favicon, PATHINFO_EXTENSION) == 'ico')
			{
				$iconfile->readICO(TL_ROOT . '/' . $favicon);
			}
			// create new icon from valid images larger than 16x16
			elseif ($objFile->isGdImage && $imgSize[0] >= 16 && $imgSize[0] >= 16)
			{

				// create small icon
				foreach ($arrIconSizes as $iconsize)
				{
					if ($imgSize[0] >= $iconsize && $imgSize[1] >= $iconsize)
					{
						$src = $this->getImage($this->urlEncode($favicon), $iconsize, $iconsize , 'crop');

						try 
						{
							// add file to ICO file, try PNG, JPG and GIF in order
							if (
									$image = @imagecreatefrompng(TL_ROOT . '/'. $src) or
									$image = @imagecreatefromjpeg(TL_ROOT . '/'. $src) or
									$image = @imagecreatefromgif(TL_ROOT . '/'. $src)
									)
							{
								$this->floIcon->addImage($image, 32);
							}
						}
						catch (Exception $e) {}
					}
				}
			}

			// if images were added, sort smallest to largest sizes
			if (count($this->floIcon->images))
			{
				// causes an static error, but we created the images in reverse order already
				//$iconfile->sortImagesBySize();
				
				// create/write the file
				$objFile = new \File($myfavicon);
				$objFile->write($this->floIcon->formatICO());
				$objFile->close();
			}
		} // favicon already exists

		// only add if there isn't already a FB Image
		if ($fbicon)
		{
			$blnOGImage = false;
			if (is_array($GLOBALS['TL_HEAD']))
			{
				foreach ($GLOBALS['TL_HEAD'] as $head)
				{
					if (strpos($head, 'og:image') > 0)
					{
						$blnOGImage = true;
						break;
					}
				}
			}
			if (!$blnOGImage)
			{
				// add Facebook OpenGraph image as primary image, thereafter other apps can add additional images
				$fbimage = $this->getImage($this->urlEncode($favicon), 512, 512 , 'box');
				$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="' . $this->Environment->base . $fbimage . '" />';
			}
		}

		// size 57px +, generate Apple version for iOS devices
		if ($imgSize[0] >= 57 && $imgSize[1] >= 57)
		{
			$maxsize = min($imgSize[0], $imgSize[1]);
			$nextsize = 16;
			foreach($arrIconSizes as $iconsize)
			{
				if ($iconsize < $maxsize)
				{
					$nextsize = $iconsize;
				}
			}
			$src = $this->getImage($this->urlEncode($favicon), $nextsize, $nextsize , 'crop');

			$GLOBALS['TL_HEAD'][] = '<link rel="apple-touch-icon" type="image/vnd.microsoft.icon" href="'.$this->Environment->base . $src.'" />';
		}

		$GLOBALS['TL_HEAD'][] = '<link rel="icon" type="image/vnd.microsoft.icon" href="'.$this->Environment->base . $myfavicon.'" />';
		$GLOBALS['TL_HEAD'][] = '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="'.$this->Environment->base . $myfavicon.'" />';
	}
  
}

?>
