<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/23/13
 * Time: 10:10 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Model;

use Zend\Authentication\Storage;

class MyAuthStorage extends Storage\Session
{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if($rememberMe == 1)
        {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}