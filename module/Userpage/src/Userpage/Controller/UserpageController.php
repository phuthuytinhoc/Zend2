<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 11/22/13
 * Time: 3:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Controller;

use Symfony\Component\Console\Application;
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

use Userpage\Form\UploadForm;
use Userpage\Model\SuccessModel;

class UserpageController extends AbstractActionController
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
//
//            $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
//            $document = new Image();
//            $document = $dm->getRepository('Application\Document\Image')->findOneBy(array('imageid' => '123456' ));
//            echo $document->getImageid();

            /////////

            $identity = $result->getIdentity();
            $userid = $identity->getUserid();
            $dm = $this->getDocumentService();

            $successModel = new SuccessModel();
            $path = $successModel->getPathImageAvatarUser($userid, $dm, 'AVA');
            $pathCover = $successModel->getPathImageAvatarUser($userid, $dm, 'COV');

            return array(
                'datauser' => $identity,
                'pathUserAvatar' => $path['pathAvaUser'],
                'pathCover' => $pathCover['pathAvaUser'],
            );
        }
    }

    //FUNCTION FOR STATUS

    public function savestatusAction()
    {
        $response = $this->getResponse();
        $status = $this->params()->fromPost('status');
        $userid = $this->getUserIdentity()->getUserid();

        $documentService = $this->getDocumentService();

        $successModel = new SuccessModel();

        $result = $successModel->saveNewStatus($status, $userid, $documentService);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'messages' => 'Đăng trạng thái thành công')));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => 'Đăng trạng thái thất bại.')));
        }
    }

    //FUNCTION FOR LOG OUT
    public function logoutAction()
    {
        $this->getAuthenService()->clearIdentity();
        return $this->redirect()->toRoute('home');
    }

    public function friendAction()
    {
        $this->indexAction();
//        $this->layout('layout/layout');
        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/friend');

        return $result;

    }

    //FUNCTION of Update info
    public function updateinfoAction()
    {
        $this->indexAction();
        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/updateinfo');
        return $result;
    }

    public function autogetuseridAction()
    {
        $response = $this->getResponse();
        $userid = $this->getUserIdentity()->getUserid();
        $successModel = new SuccessModel();

        $documentService = $this->getDocumentService();

        $pathAva = $successModel->getPathImageAvatarUser($userid, $documentService, 'AVA');
        $pathCover = $successModel->getPathImageAvatarUser($userid, $documentService, 'COV');


        return $response->setContent(\Zend\Json\Json::encode(array(
            'success' => 1,
            'userid' => $userid,
            'pathavatar' => $pathAva['pathAvaUser'],
            'pathCover' => $pathCover['pathAvaUser'],)));
    }

    //AJAX UPDATE INFO
    public function doChangePassword()
    {
        return array();
    }

    public function changepassAction()
    {
        $doWhat = $this->params()->fromPost('mode');
        $oldPass = $this->params()->fromPost('oldpass');
        $newPass = $this->params()->fromPost('newpass');
        $userid = $this->getUserIdentity()->getUserid();

        $response = $this->getResponse();

        $successModel = new SuccessModel();
        $authService = $this->getAuthenService();

        if($doWhat == 'changepass')
        {
            $result = $successModel->checkOldPassword($oldPass, $authService);
            if($result)
            {
                $documentService = $this->getDocumentService();
                $resultSavePass = $successModel->saveNewPassword($newPass, $userid, $documentService);
                if($resultSavePass)
                {
                    return $response->setContent(\Zend\Json\Json::encode(array(
                        'success' => 1,
                        'message' => 'Cập nhật mật khẩu mới thành công!.')));
                }
                else
                {
                    return $response->setContent(\Zend\Json\Json::encode(array(
                        'success' => 0,
                        'error' => 'Lỗi xảy ra. Lưu mật khẩu không thành công.')));
                }
            }
            else
            {
                return $response->setContent(\Zend\Json\Json::encode(array(
                    'success' => 0,
                    'error' => 'Mật khẩu cũ nhập vào chưa chính xác.')));
            }
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1111 )));
        }
    }

    public function changeinfoAction()
    {
        $response = $this->getResponse();

        $allData = $this->params()->fromPost();
        $userid = $this->getUserIdentity()->getUserid();

        $documentService = $this->getDocumentService();

        $successModel = new SuccessModel();

        $result = $successModel->updateNewInfo($allData,$userid,$documentService);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'message' => 'Cập nhật thông tin thành công!')));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => 'Cập nhật thông tin thất bại.')));
        }
    }

    public function changeaboutmeAction()
    {
        $response = $this->getResponse();

        $allData = $this->params()->fromPost();
        $userid = $this->getUserIdentity()->getUserid();

        $documentService = $this->getDocumentService();

        $successModel = new SuccessModel();
        $result = $successModel->updateNewAboutme($allData, $userid, $documentService);
        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'message' => 'Cập nhật thông tin thành công!')));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => 'Cập nhập thông tin thất bại.')));
        }

    }

    //FUNCTION FOR UPLOAD IMAGE AVATAR & COVER
    public function saveimageAction()
    {
        $response = $this->getResponse();

        $userid= $this->params()->fromPost('userid');
        $createdTime= $this->params()->fromPost('createdtime');
        $imageType = $this->params()->fromPost('imagetype');
        $imageType = substr($imageType, -3, 3);
        $AVAorCOV = $this->params()->fromPost('albumtype');

        $documentService = $this->getDocumentService();

        $successModel = new SuccessModel();
        $result = $successModel->saveNewImageAvatar($userid, $createdTime,$documentService, $imageType, $AVAorCOV );

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }


}