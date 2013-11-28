<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/22/13
 * Time: 3:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login-form');

        $this->setAttribute('method', 'post');
        $this->add(array('name' => 'id',
                          'attributes' => array('type' => 'hidden'),));
        $this->add(array('name' => 'username',
                         'attributes' => array('type'  => 'email',
                                               'required' => 'required',
                                               'placeholder' => 'Email',
                                               'class' => 'ajax-email'),
//                         'options'    => array('label' => 'Tên đăng nhập: '),
        ));
        $this->add(array('name' => 'password',
                         'attributes' => array('type'  => 'password',
                                               'required' => 'required',
                                               'placeholder' => 'Mật khẩu',
                                               'class' => 'ajax-password',
//                                               'pattern' => '(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$',),
                                               'min' => '6',),
//                         'options'    => array('label' => 'Mật khẩu: '),
        ));
        $this->add(array('name' => 'submit',
                         'attributes' => array(
                                               'type' => 'submit',
                                               'value' => 'Go',
                                               'id'    => 'submitbutton')));
    }
}