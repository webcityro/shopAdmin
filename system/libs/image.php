<?php

class image {
    private $target,
            $newcopy,
            $ext;

    public function setTarget($value) {
        $this->target = $value;
        $kbum = explode('.', $value);
        $this->setExt(end($kbum));
    }

    public function setNewCopy($value) {
        $this->newcopy = $value;
    }

    public function setExt($value) {
        $this->ext = $value;
    }

    public function resize ($w, $h) {
        list($w_orig, $h_orig) = getimagesize($this->target);

        $scale_ratio = $w_orig / $h_orig;

        if (($w / $h) > $scale_ratio) {
                $w = $h * $scale_ratio;
        } else {
                $h = $w / $scale_ratio;
        }
        $img = $this->createImgFromExt($this->target, $this->ext);
        $ext = strtolower($this->ext);
        $tci = imagecreatetruecolor($w, $h);

        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        return $this->save($ext, $newcopy);
    }

    public function thumb($w, $h) {
        list($w_orig, $h_orig) = getimagesize($this->target);

        $src_x = ($w_orig / 2) - ($w / 2);
        $src_y = ($h_orig / 2) - ($h / 2);

        $img = $this->createImgFromExt($this->target, $this->ext);
        $tci = imagecreatetruecolor($w, $h);

        imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);

        return $this->save($tci);
    }

    public function convertToJPG() {
        list($w_orig, $h_orig) = getimagesize($this->target);

        $img = $this->createImgFromExt($this->target, $this->ext);

        $tci = imagecreatetruecolor($w_orig, $h_orig);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
        return imagejpeg($tci, $this->newcopy, 84);
    }

    public function watermark($wtrmrk_file) {
        $watermark = imagecreatefrompng($wtrmrk_file);

        imagealphablending($watermark, false);
        imagesavealpha($watermark, true);

        $img = $this->createImgFromExt($this->target);

        $img_w = imagesx($img);
        $img_h = imagesy($img);

        $wtrmrk_w = imagesx($watermark);
        $wtrmrk_h = imagesy($watermark);

        $dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
        $dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image

        imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
        imagejpeg($img, $this->newcopy, 100);
        imagedestroy($img);
        return imagedestroy($watermark);
    }

    private function createImgFromExt() {
        if ($this->ext == "gif") {
            $img = imagecreatefromgif($this->target);
        } else if($this->ext =="png") {
            $img = imagecreatefrompng($this->target);
        } else {
            $img = imagecreatefromjpeg($this->target);
        }
        return $img;
    }

    private function save($tci) {
        if ($this->ext == "gif") {
            return imagegif($tci, $this->newcopy);
        } else if($this->ext =="png") {
            return imagepng($tci, $this->newcopy);
        } else {
            return imagejpeg($tci, $this->newcopy, 84);
        }
    }
}