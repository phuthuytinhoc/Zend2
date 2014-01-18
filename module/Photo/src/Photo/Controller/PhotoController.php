<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/25/13
 * Time: 8:30 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Photo\Controller;

use Photo\Model\PhotoModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PhotoController extends AbstractActionController
{

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

    public function indexAction()
    {
        $result = $this->getAuthenService();

        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            $layout = $this->layout();
            $layout->setTemplate('layout/photos');

            date_default_timezone_set('Asia/Ho_Chi_Minh');

            $photoModel = new PhotoModel();
            $dm = $this->getDocumentService();
            $actionUser = $this->getUserIdentity()->getUserid();
            $actionLocation = $this->params()->fromQuery('user');
            if($actionLocation == null)
            {
                $actionLocation = $actionUser;
            }

            $listImageAvatar = $photoModel->getListImageAvatar($actionLocation, 'AVA',$dm);

//            var_dump($listImageNormal);die();

            return array(
                'listAvatar' => $listImageAvatar,
            );
        }
    }

    public function coverAction()
    {
        $result = $this->getAuthenService();

        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            $this->layout()->setTemplate('layout/photos');

            $newViewMode = new ViewModel();
            $newViewMode->setTemplate('photo/photo/index');

            $dm = $this->getDocumentService();
            $actionUser = $this->getUserIdentity()->getUserid();
            $actionLocation = $this->params()->fromQuery('user');
            if($actionLocation == null)
            {
                $actionLocation = $actionUser;
            }

            $photoModel = new PhotoModel();

            $listCover = $photoModel->getListImageAvatar($actionLocation, "COV", $dm);

            $newViewMode->setVariables(array(
                'listCover' => $listCover,
            ));

            return $newViewMode;
        }
    }

    public function normalAction()
    {
        $result = $this->getAuthenService();

        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            $this->layout()->setTemplate('layout/photos');

            $newViewMode = new ViewModel();
            $newViewMode->setTemplate('photo/photo/index');

            $dm = $this->getDocumentService();
            $actionUser = $this->getUserIdentity()->getUserid();
            $actionLocation = $this->params()->fromQuery('user');
            if($actionLocation == null)
            {
                $actionLocation = $actionUser;
            }

            $photoModel = new PhotoModel();

            $listNormal = $photoModel->getListImageAvatar($actionLocation, "NOR", $dm);


            $newViewMode->setVariables(array(
                'listNormal' => $listNormal,
            ));

            return $newViewMode;
        }
    }

    public function videoAction()
    {
        $result = $this->getAuthenService();

        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
        else
        {
            $this->layout()->setTemplate('layout/photos');

            $newViewMode = new ViewModel();
            $newViewMode->setTemplate('photo/photo/video');

            $dm = $this->getDocumentService();
            $actionUser = $this->getUserIdentity()->getUserid();
            $actionLocation = $this->params()->fromQuery('user');
            if($actionLocation == null)
            {
                $actionLocation = $actionUser;
            }

            $photoModel = new PhotoModel();
            $listVideo = $photoModel->getListVideo($actionLocation, $dm);

//            var_dump($result);die();

            return array(
                'listVideo' => $listVideo,
            );
        }
    }
}