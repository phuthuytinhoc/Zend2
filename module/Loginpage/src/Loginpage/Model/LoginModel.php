<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/22/13
 * Time: 5:17 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Loginpage\Model;

use Application\Document\User;

class LoginModel
{

    public function saveUser($data)
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);

        return $user;
    }

    public function authenticateUser($data)
    {

    }



}