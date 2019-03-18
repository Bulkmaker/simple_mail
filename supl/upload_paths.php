<?php
/**
 * Created by PhpStorm.
 * User: wintermute
 * Date: 15.05.2018
 * Time: 16:19
 */

//echo __DIR__;

class FilesUploadPath {

    public $image_path;
    public $image_path_uploaded;

    public function __construct()
    {
        $this->image_path = sprintf("/home/a/andreits/brusmir.ru/public_html/uploads");
        $this->image_path_uploaded = sprintf("http://brusmir.ru/uploads/");
    }
}