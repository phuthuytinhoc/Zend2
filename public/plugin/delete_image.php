<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/10/14
 * Time: 11:54 AM
 * To change this template use File | Settings | File Templates.
 */

$path = '../uploads/';
$imageID = null;

if(isset($_POST['imageID'])){
    $imageID = $_POST['imageID'];
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $result1 = $path.$imageID.'.jpg';
    $result2 = $path.$imageID.'.png';
    $check1 = @unlink($result1);
    $check2 = @unlink($result2);
    if($check1 == true || $check2 == true ){
       echo '1';
    }
    else{
        echo '0';
    }
}
else
{
    echo '0';
}

