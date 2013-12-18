<?php

$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
$max_size = 20000 * 1024; // max file size
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
    if( ! empty($_FILES['image']) ) {
        // get uploaded file extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        // looking for format and size validity
        if (in_array($ext, $valid_exts) AND $_FILES['image']['size'] < $max_size) {
            $imageName = 'IMG'.$userid.$createdTime.'NOR.'.$ext;
            $path = $path .$imageName;
            // move uploaded file from temp to uploads directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
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