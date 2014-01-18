<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/28/13
 * Time: 10:40 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Fanpage\Controller;

use Fanpage\Model\FanpageModel;
use Fanpage\Model\FanpageIndexModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Json;
use Zend\XmlRpc\Generator\DomDocument;

class FanpageController extends AbstractActionController
{
    public $allPageID = 'abc';
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

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $dm     = $this->getDocumentService();
        $indexModel = new FanpageIndexModel();

        $userID = $this->getUserIdentity()->getUserid();
        $pageID = $this->params()->fromQuery('pageID');

        $checkExistsPage = $indexModel->checkExistsPage($pageID, $dm);

        if($checkExistsPage == false)
        {
           $newVM = new ViewModel();
           $newVM->setTemplate('fanpage/fanpage/notfound');
           return $newVM;
        }

        $layout = $this->layout();
        $layout->setTemplate('layout/fanpage');

        $checkRole = $indexModel->checkUserOwnerPage($userID, $pageID, $dm);

        $pageInfo = $indexModel->bindingDataPageIndex($userID, $pageID, $dm);

        $bindingPage = $indexModel->getDataIndexFanpage($pageID, $dm);

        if($checkRole == "ADMIN")
        {
            $avaWho = $indexModel->getPathAvatarofPageorUser($pageID, $dm);
        }
        else
        {
            $avaWho = $indexModel->getPathAvatarofPageorUser($userID, $dm);
        }

        $bindSTTonWall = $indexModel->loadStatusUseronPageWall($pageID, $dm);

//        var_dump($pageInfo['bindingImage']['imageSlideShow']);die();

        return array(
            'pageID'    => $pageID,
            'userNow'   => $userID,
            'checkRole' => $checkRole,
            'pageInfo'  => $pageInfo,
            'binding'   => $bindingPage,
            'avaWho'    => $avaWho,
            'userPost'  => $bindSTTonWall,
        );
    }

    public function editinfoAction()
    {
        $result = $this->getAuthenService();
        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }

        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();
        $userid = $this->getUserIdentity()->getUserid();
        $pageID = $this->params()->fromQuery('pageID');

        $layout = $this->layout();
        $layout->setTemplate('layout/fanpage-config');

        $result = new ViewModel();
        $result->setTemplate('fanpage/fanpage/editinfo');

        $checkOwner = $fanpageModel->checkOwnerPage($userid, $pageID, $dm);

//                var_dump($checkOwner);die();

        if(!$checkOwner)
        {
            return $this->redirect()->toRoute('home');
        }
        //get PageID && get UserID owner
        $values = $fanpageModel->getFanpageInfoByUserID($pageID, $dm);
        //get image path
        $imagePath = $fanpageModel->getBindingInfo($pageID, $dm);

//        var_dump($imagePath);die();

        $result->setVariables(array(
            'pageInfo' => $values,
            'bindingInfo' => $imagePath,
        ));

//        var_dump($fanpageModel->checkExistsAlbumofPage('page1388716637', 'LOGO', $dm));die();

        return $result;
    }

    ////////////START INSERT NEW COMMENT////////////////
    public function loadcommentbyidAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $indexModel = new FanpageIndexModel();

        $result = $indexModel->loadCommentbyActionID($data, $dm);

        if($result != null){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'commentData' => $result,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }


    }

    public function insertnewcommentAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $indexModel = new FanpageIndexModel();

        $result = $indexModel->insertNewComment($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }
    ////////////END INSERT NEW COMMENT////////////////

    //////START FUNCTION FOR AJAX FANPAGE INDEX////////////////
    public function insertpagestatusAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $indexModel = new FanpageIndexModel();

        $result = $indexModel->insertStatusPage($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }
    //////END FUNCTION FOR AJAX FANPAGE INDEX////////////////


    //FUNCTION FOR AJAX FANPAGE CONFIG

    public function bindingdataimageAction()
    {
        $response = $this->getResponse();
        $userID = $this->getUserIdentity()->getUserid();
        $pageID = $this->params()->fromQuery('pageID');

        $indexModel = new FanpageIndexModel();
        $dm = $this->getDocumentService();
        $value = $indexModel->getDataImageBindingEffect($userID, $pageID, $dm);
        if($value != null)
        {
            return $response->setContent(\Zend\Json\Json::encode($value));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode('error'));
        }

    }


    public function getalbumpageAction()
    {
        $response = $this->getResponse();
        $userID = $this->getUserIdentity()->getUserid();
        $pageID = $this->params()->fromQuery('pageID');
        $indexModel = new FanpageIndexModel();
        $dm = $this->getDocumentService();
        $value = $indexModel->getDataImageBindingEffect($userID, $pageID, $dm);

        $newArr = array(
            'name' => (string)$value['name'],
            'description' => (string)$value['description'],
            'places' => $value['places']
        );
        return json_encode($newArr);
//        return $response->setContent(\Zend\Json\Json::encode(array(
//            'success' => 1,
//        )));
    }

    public function savepageimageAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->savePageImage($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function updatepagemapinfoAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->updatePageMapInfo($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function updatepageinfoAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->updateBasicInfoPage($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function updatealbumlocationAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->createAlbumLocationPage($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function createsubalbumAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->createSubAlbum($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function bindingalbuminfoAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->bindingAlbumData($data, $dm);

        return $response->setContent(\Zend\Json\Json::encode(array(
            'success'      => 1,
            'bindingAlbum' => $result,
        )));
    }

    public function updatealbuminfoAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->updateAlbumInfobyAlbumID($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 0,
            )));
        }
    }

    public function deletealbumimageAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->deleteAlbumImage($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 0,
            )));
        }
    }

    public function insertnewimageofalbumAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->insertNewImageofAlbum($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 0,
            )));
        }
    }

    public function bindingimageofalbumAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->bindingAllImageofAlbum($data, $dm);

        if($result != null)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'binding' => $result,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'      => 0,
            )));
        }
    }

    public function bindinginfoimageofalbumAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->bindingInfoOfImage($data, $dm);

        if($result != null)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'        => 1,
                'bindingInfoImg' => $result,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'        => 0,
            )));
        }
    }

    public function updateinfoimageAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->updateInfoImageofAlbum($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

    public function deleteimageofalbumAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $fanpageModel = new FanpageModel();

        $result = $fanpageModel->deleteImageofAlbum($data, $dm);

        if($result){
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }else{
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }



}