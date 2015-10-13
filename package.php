<?php
/**
 * Created by PhpStorm.
 * User: gdahan
 * Date: 05/10/2015
 * Time: 09:40
 */

error_reporting(-1);
ini_set('display_errors', 'On');

$result = array("error" => "" ,
    "version"=>"" ,
    "MD5checksum"=> "",
    "download link"=> "",
    "release link"=> "",
    "date" => "");

if (isset($_GET["package"])) {
    $package = $_GET["package"];
    $packagePath = dirname(__FILE__) . "/packages/".$package;
    $output = array();

    $dir = new DirectoryIterator($packagePath);
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot()) {
            switch($fileinfo->getExtension()) {
                case "gz":
                    exec("md5sum ".$fileinfo->getPathname()." | awk '{ print $1 }'",$output);
                    $result["MD5checksum"] = $output[0];
                    $result["download link"] = "/packages/".$package."/".$fileinfo->getFilename();
                    $result["date"] = date("d/m/Y H:i:s",$fileinfo->getCTime());
                    $result["version"] = $package;
                    break;
                case "doc" :
                    $result["release link"] = "/packages/".$package."/".$fileinfo->getFilename();
            }
        }
    }
} else {
    $result["error"] = "Impossible de recuperer le nom du package via GET";
}

echo json_encode($result);