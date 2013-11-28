<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 11/21/13
 * Time: 1:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('register-form');

        $this->setAttribute('method' , 'post');

        $this->add(array(
            'name' => 'firstname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'percent',
                'placeholder' => 'Tên',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'lastname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'percent',
                'placeholder' => 'Họ',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'placeholder' => 'Email',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Mật khẩu',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'password_verify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Nhập lại mật khẩu',
                'required' => 'required',
            ),
            'options' => array(
            ),
        ));

        $this->add(array('name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id'    => 'submitReg')
        ));
    }
}