<?php
/*
 *  file name :
 *	Upload.php
 *  Describe :
 *	檔案上傳模組 搭配throw new Exception
 *  Start Date :
 *	2015.10.23
 *  Author :
 *	Lanker
 */

class Upload {
    protected $haveError;

    public function __construct() {
	$haveError = false;
    }

    public function uploadFinish($newFileName) {
	$data = Array();
	$data['success'] = Array();
	$data['failed'] = Array();
	$haveError = false;
	$sussCount = 0;
	$failCount = 0;
	$errMsg = "";
	foreach($_FILES['file']['name'] as $count => $name) {
	    if($_FILES['file']['error'][$count] > 0) {
		$data['failed'][$failCount]['filename'] = $_FILES['file']['name'][$count];
		$data['failed'][$failCount++]['error'] = $_FILES['file']['error'][$count];
		$errMsg .= $_FILES['file']['error'][$count];
		$haveError = true;
	    }
	    else 
		if(file_exists($_FILES['file']['name'][$count])) {
		    $data['failed'][$failCount++]['error'] = "already exist";
		    $errMsg .= "already exist";
		    $haveError = true;
		}
		else {
		    $data['success'][$sussCount]['name'] = $_FILES['file']['name'][$count];
		    $data['success'][$sussCount]['type'] = $_FILES['file']['type'][$count];
		    $data['success'][$sussCount]['size'] = $_FILES['file']['size'][$count];
                    $type = $data['success'][$sussCount]['type'];
                    $exts = Array();
                    $exts["image/jpeg"] = ".jpg";
                    $exts["image/jpg"] = ".jpg";
                    $exts["image/png"] = ".png";
                    $exts["image/gif"] = ".gif";
                    $exts["image/bmp"] = ".bmp";
                    if(is_dir(__DIR__. "/../imgs/tmp") == false)
                        mkdir(__DIR__. "/../imgs/tmp");
		    move_uploaded_file($_FILES['file']['tmp_name'][$count], "imgs/tmp/$newFileName". $exts[$type]); //. $_FILES['file']['name'][$count]);
		    $data['success'][$sussCount]['newName'] = $newFileName. $exts[$type];
		    ++$sussCount;
		}
	}
	if($haveError)
	    throw new Exception(json_encode($data['failed']));
	return $data['success'];
    }
}
