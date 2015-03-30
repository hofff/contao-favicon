<?php

/**
 * @copyright hofff.com 2014
 * @copyright Thyon Design 2010
 * @author John Brand <http://www.thyon.com>
 * @author Oliver Hoff <oliver@hofff.com>
 * @license LGPL
 */

namespace Hofff\Contao\Favicon;

/*
 * XHTML:
 * <link rel="icon" type="image/vnd.microsoft.icon" href="/somepath/image.ico" />
 * <link rel="icon" type="image/png" href="/somepath/image.png" />
 * <link rel="icon" type="image/gif" href="/somepath/image.gif" />
 *
 * APPLE:
 * <link rel="apple-touch-icon" href="/somepath/image.ico" />
 * <link rel="apple-touch-icon" href="/somepath/image.png" />
 * <link rel="apple-touch-icon" href="/somepath/image.gif" />
 */

/**
 * Class MyFavicon
 *
 * Provide methods create multi-rez ICO file from PNG, JPG, GIF or link ICO files directly
 *
 * @copyright Thyon Design 2010
 * @author John Brand <http://www.thyon.com>
 */
class Favicon extends \Frontend {

	/**
	 * @var floIcon
	 */
	protected $floIcon;

	/**
	 */
	public function __construct() {
		parent::__construct();
		$this->floIcon = new floIcon;
	}

	/**
	 * @param \PageModel $page
	 * @param \LayoutModel $layout
	 * @param \PageRegular $pageDriver
	 * @return void
	 */
	public function hookGeneratePage(\PageModel $page, \LayoutModel $layout, \PageRegular $pageDriver) {
		$rootPage = $this->getPageDetails($page->rootId);

		if(!$rootPage->hofff_favicon) {
			return;
		}

		$file = \FilesModel::findByUuid($rootPage->hofff_favicon_src);

		if(!$file || !is_file(TL_ROOT . '/' . $file->path)) {
			return;
		}

		$this->createIcon(
			$file->path,
			$rootPage->hofff_favicon_addRootAlias ? $rootPage->alias : '',
			$rootPage->hofff_favicon_facebook
		);
	}

	/**
	 * @param string $favicon
	 * @param string $root
	 * @param boolean $fbicon
	 * @param string $location
	 */
	public function createIcon($favicon, $root = '', $fbicon = false, $location = '') {
		// default sizes for icon set
		$arrIconSizes = array (
			16,
			24,
			32,
			64,
			128,
			256,
			512
		);

		// default location and filename for favicon
		$myfavicon = $location . 'favicon' . (strlen($root) ? '-' . $root : '') . '.ico';

		// open original file, to see if its and image
		$objFile = new \File($favicon);

		// get image size
		$imgSize = getimagesize(TL_ROOT . '/' . $favicon);

		// create a new icon, if it doesn't exist
		if(!file_exists(TL_ROOT . '/' . $myfavicon)) {

			// if icon exists, then read it and assign
			if(pathinfo(TL_ROOT . '/' . $favicon, PATHINFO_EXTENSION) == 'ico') {
				$this->floIcon->readICO(TL_ROOT . '/' . $favicon);

			// create new icon from valid images larger than 16x16
			} elseif($objFile->isGdImage && $imgSize[0] >= 16 && $imgSize[0] >= 16) {

				// create small icon
				foreach($arrIconSizes as $iconsize) {
					if($imgSize[0] >= $iconsize && $imgSize[1] >= $iconsize) {
						$src = $this->getImage($this->urlEncode($favicon), $iconsize, $iconsize, 'crop');

						try {
							// add file to ICO file, try PNG, JPG and GIF in order
							if($image = @imagecreatefrompng(TL_ROOT . '/' . $src) or $image = @imagecreatefromjpeg(TL_ROOT . '/' . $src) or $image = @imagecreatefromgif(TL_ROOT . '/' . $src)) {
								$this->floIcon->addImage($image, 32);
							}
						} catch(Exception $e) {
						}
					}
				}
			}

			// if images were added, sort smallest to largest sizes
			if(count($this->floIcon->images)) {
				// causes an static error, but we created the images in reverse order already
				// $iconfile->sortImagesBySize();

				// create/write the file
				$objFile = new \File($myfavicon);
				$objFile->write($this->floIcon->formatICO());
				$objFile->close();
			}
		} // favicon already exists

		// only add if there isn't already a FB Image
		if($fbicon) {
			$blnOGImage = false;
			if(is_array($GLOBALS['TL_HEAD'])) {
				foreach($GLOBALS['TL_HEAD'] as $head) {
					if(strpos($head, 'og:image') > 0) {
						$blnOGImage = true;
						break;
					}
				}
			}
			if(! $blnOGImage) {
				// add Facebook OpenGraph image as primary image, thereafter other apps can add additional images
				$fbimage = \Image::get($this->urlEncode($favicon), 512, 512, 'box');
				$GLOBALS['TL_HEAD'][] = '<meta property="og:image" content="' . $this->Environment->base . $fbimage . '" />';
			}
		}

		// size 57px +, generate Apple version for iOS devices
		if($imgSize[0] >= 57 && $imgSize[1] >= 57) {
			$maxsize = min($imgSize[0], $imgSize[1]);
			$nextsize = 16;
			foreach($arrIconSizes as $iconsize) {
				if($iconsize < $maxsize) {
					$nextsize = $iconsize;
				}
			}
			$src = \Image::get($this->urlEncode($favicon), $nextsize, $nextsize, 'crop');

			$GLOBALS['TL_HEAD'][] = '<link rel="apple-touch-icon" type="image/vnd.microsoft.icon" href="' . $this->Environment->base . $src . '" />';
		}

		$GLOBALS['TL_HEAD'][] = '<link rel="icon" type="image/vnd.microsoft.icon" href="' . $this->Environment->base . $myfavicon . '" />';
		$GLOBALS['TL_HEAD'][] = '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="' . $this->Environment->base . $myfavicon . '" />';
	}

}
