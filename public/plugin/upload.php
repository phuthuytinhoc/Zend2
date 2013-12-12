<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/12/13
 * Time: 4:58 PM
 * To change this template use File | Settings | File Templates.
 */




$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
$max_file_size = 20000 * 1024; #200kb
$nw = $nh = 200; # image with & height

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['createdtime']))
    {
        $createtime =  $_POST['createdtime'];
    }
    else
    {
        $createtime =  "";
    }
    if(isset($_POST['userid']))
    {
        $userid =  $_POST['userid'];
    }
    else
    {
        $userid =  "";
    }

    if ( isset($_FILES['image']) ) {
        if (! $_FILES['image']['error'] && $_FILES['image']['size'] < $max_file_size) {
            # get file extension
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            # file type validity
            if (in_array($ext, $valid_exts)) {
//                $path = '../uploads/' . uniqid()  . '.' . $ext;
                $path = '../uploads/' . 'AVA'. $userid . '_'.$createtime. '.' . $ext;
                $size = getimagesize($_FILES['image']['tmp_name']);
                # grab data form post request
                $x = (int) $_POST['x'];
                $y = (int) $_POST['y'];
                $w = (int) $_POST['w'] ? $_POST['w'] : $size[0];
                $h = (int) $_POST['h'] ? $_POST['h'] : $size[1];
                # read image binary data
                $data = file_get_contents($_FILES['image']['tmp_name']);
                # create v image form binary data
                $vImg = imagecreatefromstring($data);
                $dstImg = imagecreatetruecolor($nw, $nh);
                # copy image
                imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
                # save image
                imagejpeg($dstImg, $path);
                # clean memory
                imagedestroy($dstImg);
                echo $path;

            } else {
                echo 'Lỗi không xác định!';
            }
        } else {
            echo 'File quá nhỏ hoặc quá lớn so với quy định.';
        }
    } else {
        echo 'Chưa chọn file để upload!';
    }
} else {
    echo 'bad request!';
}