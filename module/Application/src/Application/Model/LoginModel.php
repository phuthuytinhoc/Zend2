<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/22/13
 * Time: 5:17 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Model;

use Application\Document\User;

class LoginModel
{


    public function getTimestampNow()
    {
        $date = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $date->getTimestamp();
    }

    public function saveUser($data, $dm)
    {
        $user = new User();
        $firstname = ($data['firstname']);
        $lastname = ($data['lastname']);
        $email = $data['email'];
        $password = $data['password'];
        ////
        $timestampNow = $this->getTimestampNow();
        $userid = 'user'.$timestampNow;


        $result = $dm->getRepository('Application\Document\User')->findOneBy(array('email' => $email));
        if($result == null)
        {
            $user->setUserid($userid);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setCreatedtime($timestampNow);
            //luu vao database
            $dm->persist($user);
            $dm->flush();
            return true;
        }
        else
        {
            return false;
        }
    }


}