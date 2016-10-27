<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_FILES['uploadfilter']) && !empty($_FILES['uploadfilter'])){
        if(in_array($_FILES['uploadfilter']['type'], array('application/x-zip-compressed', "application/octet-stream", "application/zip")) || $_FILES['uploadfilter']['tmp_name'] == 'siphon-wordpress-plugin.zip'){
            //user uploaded the zip

            $zip = new ZipArchive;
            $res = $zip->open($_FILES['uploadfilter']['tmp_name']);
            if ($res === TRUE) {
                check_admin_referer('upload_filter_');
                $uploadDir = wp_upload_dir();
                $targetDir = $uploadDir['basedir']."/siphon/";
                if(!file_exists($targetDir)){
                    mkdir($targetDir, 0755, TRUE);
                }
                $zip->extractTo($targetDir);
                $zip->close();
                rename($targetDir."siphon-filter-file.php",$targetDir."siphonfilterloaded.php");
            }
        }
        else{
            check_admin_referer('upload_filter_');
            $uploadDir = wp_upload_dir();
            $targetDir = $uploadDir['basedir']."/siphon/";
            if(!file_exists($targetDir)){
                mkdir($targetDir, 0755, TRUE);
            }
            move_uploaded_file($_FILES['uploadfilter']['tmp_name'], $targetDir."siphonfilterloaded.php");

        }

    }
    else{
        echo"<div class='notice notice-warning is-dismissible'>
                <p>Not a proper file. Please upload a new filter file.</p>
             </div>";
    }
}
$extensions = get_loaded_extensions();
$errors = "";
if(!in_array("curl", $extensions)){
    $errors.= "<span  style='color:red;'>curl not installed! </span></br>";
}
else{}
if(!in_array("json", $extensions)){
    $errors.= "<span  style='color:red;'>json not installed! </span></br>";
}
else{}
if(is_int(PHP_MAJOR_VERSION) && PHP_MAJOR_VERSION < 5 && is_int(PHP_MINOR_VERSION) && PHP_MINOR_VERSION < 3){
    $errors.= "<span  style='color:red;'>PHP Version is to low! </span></br>";
}
else{}
$memoryLimit = ini_get('memory_limit');
if(preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)){
    if($matches[2] == 'M'){
        $memoryLimit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
    }
    else if($matches[2] == 'K'){
        $memoryLimit = $matches[1] * 1024; // nnnK -> nnn KB
    }
}
if($memoryLimit < 32 * 1024 * 1024){
    $errors.= "<span style='color:red;'>Not enough php memory! Memory limit is currently: ".(($memoryLimit/1024)/1024)."Mb</span></br>";
}
else{}
if($errors == ""){

    echo "
    <div class='wrap'>
        <h2>Siphon Traffic Filter</h2>
        <form method='post' action='admin.php?page=siphon' enctype='multipart/form-data'>
            <label for='uploadfilter'>Upload new filter file</label>".
         wp_nonce_field( 'upload_filter_')
         ."<input type='file' name='uploadfilter' id='uploadfilter'>
            ".get_submit_button('Upload')."
        </form>
    </div>";

    $upload_dir = wp_upload_dir();
    if(!file_exists($upload_dir['basedir']."/siphon/siphonfilterloaded.php")){
        echo "<div class='notice notice-warning'>
                <p>No file is currently installed. Please upload a filter file to start using Siphon.</p>
              </div>";
    }
    else{
        if(file_get_contents($upload_dir['basedir']."/siphon/siphonfilterloaded.php") == ""){
            echo "<div class='notice notice-warning'>
                <p>The current filter file is not properly formatted. Please upload a new filter file to start using Siphon.</p>
              </div>";
        }
        else{
            echo "<div class='notice notice-success'>
                <p>A filter file is installed and ready for use.</p>
              </div>";
        }
    }
}
else{
    echo "<div class='notice notice-error'>
                <p>This server is missing components for Siphon to work</p>
                <p>".$errors."</p>
          </div>";
}
?>

