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
            $userList = $homepageModel->getUserFriend($dm, $userID);
            $actionList = $homepageModel->getAllAction($dm, $userList, $userdata->getUserid());
//
//            var_dump($actionList);
//            die();

            return array (
                'userdata'      => $userdata,
                'useravatar'    => $useravatar,
                'actionlist'    => $actionList,
            );
        }
    }

    public function savelikeAction() {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $homepageModel = new HomepageModel();
        $data = $this->params()->fromPost();

        $result = $homepageModel->saveLike($dm, $data);
        $count = $homepageModel->countlike($dm, $data['actionid']);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => $result,
                'number'  => $count,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,)));
        }
    }

    public function dislikeAction() {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $homepageModel = new HomepageModel();
        $data = $this->params()->fromPost();

        $result = $homepageModel->dislike($dm, $data);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'number' => $result,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,)));
        }
    }

    public function savecommentAction() {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $homepageModel = new HomepageModel();
        $data = $this->params()->fromPost();

        $result = $homepageModel->saveComment($dm, $data);
        $username = $homepageModel->getUserName($dm, $data['actionuser']);
        $avatarpath = $homepageModel->getUserAvatarPath($dm, $data['actionuser']);
        $createdtime = $data['actiontime'];
        $commentcontent = $data['commentcontent'];

        if ($result) {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'           => 1,
                'username'          => $username,
                'avatarpath'        => $avatarpath,
                'createdtime'       => $createdtime,
                'commentcontent'    => $commentcontent,
                'actionid'          => $result,
            )));
        }else {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,)));
        }
    }

    public function searchAction() {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $homepageModel = new HomepageModel();
        $data = $this->params()->fromPost();

        $friendList = $homepageModel->searchFriend($dm, $data['string'], $data['currentuser']);
        $placeList = $homepageModel->searchPlace($dm, $data['hashtag']);

        if ($friendList !== false) {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'friendlist' => $friendList,
                'placelist' => $placeList,
            )));
        }else {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,)));
        }
    }
}