<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 11/21/13
 * Time: 1:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Loginpage\Form;

use Zend\Form\Form;

class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('register-form');

        $this->setAttribute('method' , 'post');
        $this->add(array());
    }
}