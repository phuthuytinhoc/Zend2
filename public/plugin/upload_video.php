<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/17/13
 * Time: 11:14 AM
 * To change this template use File | Settings | File Templates.
 */

$valid_exts = array('wmv', 'mp4', 'flv', 'ogv'); // valid extensions
$max_size = 100000 * 1024; // max file size
$path = '../uploads/'; // upload directory
$userid = $createdTime = uniqid();

if(isset($_POST['timestamp']))
{
    $createdTime = $_POST['timestamp'];
}
if(isset($_POST['userid']))
{
    $userid = $_POST['userid'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if( ! empty($_FILES['video']) ) {
        // get uploaded file extension
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        // looking for format and size validity
        if (in_array($ext, $valid_exts) AND $_FILES['video']['size'] < $max_size) {
            $imageName = 'VID'.$userid.$createdTime.'.'.$ext;
            $path = $path .$imageName;
            // move uploaded file from temp to uploads directory
            if (move_uploaded_file($_FILES['video']['tmp_name'], $path)) {
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

?>