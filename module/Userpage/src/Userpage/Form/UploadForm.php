<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/12/13
 * Time: 4:11 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Form;

use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('client-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');


        $this->add(array(
            'name' => 'profilename',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Profile Name',
            ),
        ));


        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        ));
    }
}