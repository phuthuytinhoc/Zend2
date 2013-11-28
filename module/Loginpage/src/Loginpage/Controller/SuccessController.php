<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/24/13
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;


class SuccessController extends AbstractActionController
{
    public function indexAction()
    {
        $session = new Container('user');
        echo $session->username;

        return new ViewModel(array());
    }
}