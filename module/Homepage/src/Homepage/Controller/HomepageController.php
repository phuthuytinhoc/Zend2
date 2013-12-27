<?php

namespace Homepage\Controller;


use Homepage\Model\HomepageModel;
use Symfony\Component\Console\Application;
use Userpage\Model\FriendModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Validator\File\Upload;
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Helper\Json;
use Zend\Form\Form;

use Application\Document\User;
use Application\Document\Action;
use Application\Document\Album;
use Application\Document\Status;
use Application\Document\Image;

use Userpage\Model\SuccessModel;

class HomepageController extends AbstractActionController {

    public function getAuthenService()
    {
        $authen = $this->getServiceLocator()->get('doctrine.authenticationservice.odm_default');
        return $authen;
    }

    public function getDocumentService()
    {
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        return $dm;
    }

    public function getUserIdentity()
    {
        $result = $this->getAuthenService();
        if($result->hasIdentity())
            return $result->getIdentity();

        return null;
    }

    public function indexAction() {

        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/homepage');

        $result = $this->getAuthenService();
        if (!$result->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }else {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $dm = $this->getDocumentService();
            $homepageModel = new HomepageModel();

            $userID = $this->getUserIdentity()->getUserid();

            $userdata = $homepageModel->getUserInfo($dm, $userID);
            $useravatar = $homepageModel->getPathImageAvatarUser($dm, $userID, 'AVA');
            $userfiend = $homepageModel->getUserFriend($dm, $userID);

            var_dump($userfiend);
            die();

            return array (
                'userdata'      => $userdata,
                'useravatar'    => $useravatar,
            );
        }
    }
}