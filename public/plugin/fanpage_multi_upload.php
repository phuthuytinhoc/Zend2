<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/3/14
 * Time: 4:29 PM
 * To change this template use File | Settings | File Templates.
 */

$extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
$max_size = 20000 * 1024; // max file size
$path = '../uploads/'; // upload directory
$pageid = $createdTime = uniqid();
$albumType = "SLI";
$count = 0;

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



if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['files']))
{
    // loop all files
    foreach ( $_FILES['files']['name'] as $i => $name )
    {
        // if file not uploaded then skip it
        if ( !is_uploaded_file($_FILES['files']['tmp_name'][$i]) )
            continue;

        // skip large files
        if ( $_FILES['files']['size'][$i] >= $max_size )
            continue;

        // skip unprotected files
        if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
            continue;

//        $ext = strtolower(pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION));

        $timebyInt = intval($createdTime) + $count;

        $imageName = 'IMG'.$pageid.$timebyInt.$albumType.'.png';
        $newPath = $path .$imageName;

        // now we can move uploaded files

        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $newPath)) {
            $imageName = '';
            $count++;
        }
    }
    echo $count;

}else
    echo 'error';