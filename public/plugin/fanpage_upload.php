<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/2/14
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */



$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
$max_size = 20000 * 1024; // max file size
$path = '../uploads/'; // upload directory
$pageid = $createdTime = uniqid();
$albumType = "LOGO";

if(isset($_POST['timestamp']))
{
    $createdTime = $_POST['timestamp'];
}
if(isset($_POST['pageid']))
{
    $pageid = $_POST['pageid'];
}
if(isset($_POST['albumType']))
{
    $albumType = $_POST['albumType'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if( ! empty($_FILES['image']) ) {
        // get uploaded file extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        // looking for format and size validity
        if (in_array($ext, $valid_exts) AND $_FILES['image']['size'] < $max_size) {
            $imageName = 'IMG'.$pageid.$createdTime.$albumType.'.'.$ext;
            $path = $path .$imageName;
            // move uploaded file from temp to uploads directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path))
            {

                echo $imageName;
            }
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

function resize($width, $height)
{
    /* Get original image x y*/
    list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
    /* calculate new image size with ratio */
    $ratio = max($width/$w, $height/$h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);
    /* new file name */
    $path = 'uploads/thumbs/'.$width.'x'.$height.'_'.$_FILES['image']['name'];
    /* read binary data from image file */
    $imgString = file_get_contents($_FILES['image']['tmp_name']);
    /* create image from string */
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);
    imagecopyresampled($tmp, $image,
        0, 0,
        $x, 0,
        $width, $height,
        $w, $h);
    /* Save image */
    switch ($_FILES['image']['type']) {
        case 'image/jpeg':
            imagejpeg($tmp, $path, 100);
            break;
        case 'image/png':
            imagepng($tmp, $path, 0);
            break;
        case 'image/gif':
            imagegif($tmp, $path);
            break;
        default:
            exit;
            break;
    }
    return $path;
    /* cleanup memory */
    imagedestroy($image);
    imagedestroy($tmp);
}

?>