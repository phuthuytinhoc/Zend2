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
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Helper\Json;

use Application\Document\User;
use Application\Document\Action;
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
//            $ketqua = new Action();
//            $ketqua = $dm->getRepository('Application\Document\Action')->findOneBy(array('actionid' => 'ACT123456' ));
//            echo $ketqua->getActionid();

//            $date = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
//            echo $date->getTimestamp(); die();

            /////////

            $identity = $result->getIdentity();

            return new ViewModel(array(
                'datauser' => $identity,

            ));
        }
    }

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

    public function updateinfoAction()
    {
        $this->indexAction();
        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/updateinfo');
        return $result;
    }

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


}