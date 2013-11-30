<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 11/22/13
 * Time: 3:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;

class UserpageController extends AbstractActionController
{

    public function getAuthenService()
    {
        $authen = $this->getServiceLocator()->get('doctrine.authenticationservice.odm_default');
        return $authen;
    }

    public function indexAction()
    {
        if(!$this->getAuthenService()->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            return new ViewModel(array());
        }
    }

    public function logoutAction()
    {
        $this->getAuthenService()->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    public function friendAction()
    {
        return new ViewModel(array());
    }

}