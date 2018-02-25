<?php

class downloadImage {

	function __construct() {}

	public function downloadImage($url) {
		$kbum = explode('.', $url);
		$imgExt = end($kbum);
		$kbum = explode('?', $imgExt);
		$imgExt = end($kbum);
		$kbum = explode('/', $imgExt);
		$imgExt = end($kbum);
		$newImageName = oc::getProductsImageDIR().md5(microtime().uniqid(rand(0, 999999))).'.'.$imgExt;
		$remoteImage = fopen($url, 'r');
		file_put_contents(oc::getImagesDIR().$newImageName, $remoteImage);
		fclose($remoteImage);
		return $newImageName;
	}

	public function downloadThumb($url, $thumbName) {
		$kbum = explode('/', $thumbName);
		$thumbName = end($kbum);
		$thumbPath = oc::getProductsImageThumbDIR().$thumbName;
		$remoteImage = fopen($url, 'r');
		file_put_contents(oc::getImagesDIR().$thumbPath, $remoteImage);
		fclose($remoteImage);
		return $thumbPath;
	}

	public function makeThumb($thumb)	{
		$imgObj = new image();
		$imgObj->setTarget(oc::getImagesDIR().$thumb);
		$imgObj->setNewCopy(oc::getImagesDIR().$thumb);
		return $imgObj->thumb(100, 100);
	}
}