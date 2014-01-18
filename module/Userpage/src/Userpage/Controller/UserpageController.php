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
use Userpage\Model\CreatePageModel;
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
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $successModel = new SuccessModel();
            $dm = $this->getDocumentService();
//            $dataInfo = "";

            $actionUser = $this->getUserIdentity()->getUserid();
            $actionLocation = $this->params()->fromQuery('user');

            if($actionLocation == null)
            {
                $actionLocation = $actionUser;
            }

            if($actionUser == $actionLocation)
            {//dang o trang ca nhan cua chinh nguoi dung
                $dataInfo        = $dataUserNow   = $successModel->getPrivateInfomationUser($actionUser, $dm);
                $pathSmallAvatar = $pathBigAvatar = $successModel->getPathImageAvatarUser($actionUser, $dm, "AVA");
                $pathCover       = $successModel->getPathImageAvatarUser($actionUser, $dm, 'COV');
                $activityContent = $successModel->getAllContentPrivatePage($actionUser, $actionUser, $dm);
                $checkFriend = "HOME";
            }
            else
            {//dang o trang ca nhan ban be actionLocation
                $dataInfo        = $successModel->getPrivateInfomationUser($actionLocation, $dm);
                $dataUserNow     = $successModel->getPrivateInfomationUser($actionUser, $dm);
                $pathSmallAvatar = $successModel->getPathImageAvatarUser($actionUser, $dm, "AVA");
                $pathBigAvatar   = $successModel->getPathImageAvatarUser($actionLocation, $dm, "AVA");
                $pathCover       = $successModel->getPathImageAvatarUser($actionLocation, $dm, 'COV');
                $activityContent = $successModel->getAllContentPrivatePage($actionLocation, $actionLocation, $dm);
                $checkFriend     = $successModel->checkFriend($actionUser, $actionLocation, $dm);
            }

            $infoUseronWal =  $successModel->getInfoUserByID($dm);
            $infoUserCommented = $successModel->getInfoUserbyActionType($dm);
            $getNotifFriend = $successModel->getRequestFriend($actionUser, $actionLocation, $dm);

            //Phan Friend
            $friendModel = new FriendModel();
            $listFriendID = $friendModel->getListFriend($actionLocation, $dm);

//            var_dump($actionLocation);die();

            //lay anh bum anh dai dien gan day cua user
            $albumAvatarHome = $successModel->getAlbumAvatarHome($actionLocation, $dm, 'AVA');
            $listFriendAccepted = $successModel->getFriendByStatus($actionLocation, $dm, 'ACCEPTED');

            //lay danh sach like status , comment
            $countLikes = $successModel->getCountLikeActionID($actionLocation, $dm);

//            var_dump($actionLocation);
            //getListActionID of Comment
            $actionIDofCMT = $successModel->getActionIDofCommentbyCMTID($actionLocation, $dm);


//            $friendModel = new FriendModel();
//            $result = $friendModel->getResultSearch($actionUser, $dm);

//            var_dump($result); die();



            return array(
                //actionID of comment
                'actionIDofCMT'            => $actionIDofCMT,
                //count likes
                'countLikes'                => $countLikes['countLikes'],
                'checkLikeYourself'         => $countLikes['checkLikeYourself'],
                //tra ve thong tin ban be user
                'listFriendID'              => $listFriendID,
                'albumAvatarHome'           => $albumAvatarHome,
                'listFriendAccepted'        => $listFriendAccepted,
                //tra ve actionUser va actionLocation
                'actionUserID'              => $actionUser,
                'actionLocationID'          => $actionLocation,
                'infoUseronWal'             => $infoUseronWal,
                'infoUserCommented'         => $infoUserCommented,
                //check Friend
                'checkFriend'               => $checkFriend,
                'getNotifFriend'            => $getNotifFriend,
                //lay thong tin ca nhan cua user
                'datauser'                  => $dataInfo,
                'dataGuest'                 => $dataUserNow,
                //lay link anh dai dien & anh cover user
                'smallAvatar'               => $pathSmallAvatar,
                'bigAvatar'                 => $pathBigAvatar,
                'imageCover'                => $pathCover,
                //lay thong tin toan bo activity Content cua user: STT PLACE IMAGE VIDEO
                    //danh sach cac actionID
                    'arrayTrueActionID'     => $activityContent['arrayTrueActionID'],
                    //lay danh sach cac action Type
                    'arrayActionType'       => $activityContent['arrayActionType'],
                    //lay array status content
                    'arrayStatusContent'    => $activityContent['arrayStatusContent'],
                    //lay array image content
                    'arrayPathALLIMAGE'     => $activityContent['arrayPathALLIMAGE'],
                    //lay array video content
                    'arrayPathALLVIDEO'     => $activityContent['arrayPathALLVIDEO'],
                    //lay array share content
                    'arrayALLSharePlace'    => $activityContent['arrayALLSharePlace'],
                    //lay thoi gian createdtime action
                    'arrayActionCreatedTime'=> $activityContent['arrayActionCreatedTime'],
                //Lay toan bo commentID tuong ung voi actionID
                    //lay array commentID
                    'allCommentID'          => $activityContent['allCommentID'],
                    //lay array comment content
                    'arrayCommentContent'   => $activityContent['arrayCommentContent'],

                //test
                'arrayActuserAclocation' => $activityContent['arrayActuserAclocation'],

                //Bang Action
//                'arrayActionUser'       => $allContent['arrayActionUser'],
//                'arrayActionLocation'   => $allContent['arrayActionLocation'],
//
//                'arrayActionCreatedTime'=> $allContent['arrayActionCreatedTime'],

            );
        }
    }

    //FUNCTION FOR STATUS

    public function savestatusAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $status = $data['status'];
        $actionUser = $data['actionUser'];
        $actionLocation = $data['actionLocation'];
        $createdTime = $this->params()->fromPost('timestamp');

        $documentService = $this->getDocumentService();

        $successModel = new SuccessModel();

        $result = $successModel->saveNewStatus($status, $actionUser,$actionLocation, $createdTime,$documentService);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'messages' => '??ng tr?ng thái thành công')));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => '??ng tr?ng thái th?t b?i.')));
        }
    }

    public function getlatestAction()
    {

    }

    //FUNCTION FOR COMMENT
    public function savecommentAction()
    {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $data = $this->params()->fromPost();
        $result = $successModel->saveNewComment($data, $dm);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,)));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,)));
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
        $result = $this->getAuthenService();
        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }
//        $this->layout('layout/layout');
        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/friend');

        $actionUser = $this->getUserIdentity()->getUserid();
        $actionLocation = $this->params()->fromQuery('user');
        if(!isset($actionLocation))
        {
            $actionLocation = $actionUser;
        }

//        var_dump($actionLocation);die();

        $dm = $this->getDocumentService();
        $friendModel = new FriendModel();
        $successModel = new SuccessModel();
        $actionUser = $this->getUserIdentity()->getUserid();

        $listFriendID = $friendModel->getListFriend($actionLocation, $dm);
        $listFriendSent = $successModel->getFriendByStatus($actionLocation, $dm, 'SENT');

//        var_dump($listFriendID); die();

        return array(
            'actionUserID'     => $actionUser,
            'actionLocationID' => $actionLocation,
            'userid'           => $actionLocation,
            'listFriendID'     => $listFriendID,
            'banchung'         => $listFriendID['banchung'],
            'relationship'     => $listFriendID['infoFriends'],
            'infoUser'         => $listFriendID['infoFriends'],
            'checkStatusFriend'=> $listFriendID['checkStatusFriend'],
            'listFriendSent'   => $listFriendSent,
        );

    }

    //FUNCTION of Update info
    public function updateinfoAction()
    {
        $result = $this->getAuthenService();
        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }

        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/updateinfo');

        $allInfo = $this->getUserIdentity();

        $aboutMe = array(
            'school' => $allInfo->getSchool(),
            'work'   => $allInfo->getWork(),
            'relationship' => $allInfo->getRelationship(),
        );
        $bacsicInfo = array(
            'lastname'  => $allInfo->getLastname(),
            'firstname' => $allInfo->getFirstname(),
            'dob'       => $allInfo->getDOB(),
            'address'   => $allInfo->getAddress(),
            'quote'     => $allInfo->getQuote(),
        );


        $result->setVariables(array(
            'aboutMe'    => $aboutMe,
            'basicInfo'  => $bacsicInfo,
        ));
        return $result;
    }

    public function autogetuseridAction()
    {
        $response = $this->getResponse();
        $allInfo = $this->getUserIdentity();
        $userid = $allInfo->getUserid();



        $successModel = new SuccessModel();

        $documentService = $this->getDocumentService();

        $pathAva = $successModel->getPathImageAvatarUser($userid, $documentService, 'AVA');
        $pathCover = $successModel->getPathImageAvatarUser($userid, $documentService, 'COV');


        return $response->setContent(\Zend\Json\Json::encode(array(
            'success'    => 1,
            'userid'     => $userid,
            'pathavatar' => $pathAva,
            'pathCover'  => $pathCover,

        )));
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

    public function getBasePath()
    {
        $uri = $this->getRequest()->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        $base = sprintf('%s://%s', $scheme, $host);
        return $base;
    }

    //FUNCTION FOR UPLOAD NORMAL IMAGE.
    public function savenormalimageAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();

        $dm = $this->getDocumentService();
        $userid = $data['userid'];
        if(substr($data['imageType'],-4, 1) == ".")
        {
            $imageType = substr($data['imageType'], -3,3);
        }
        else
        {
            $imageType = substr($data['imageType'], -4,4);
        }
        $createdTime = $data['createdTime'];
        $descript = $data['descript'];

        $successModel = new SuccessModel();
        $result = $successModel->saveNewImageNormal($userid,$createdTime, $descript, $imageType, $dm);

        if($result!=null)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => $result,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }


    }

    //FUNCTION FOR UPLOADING VIDEO.
    public function savevideoAction()
    {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $data = $this->params()->fromPost();
        $result = $successModel->saveNewVideo($dm, $data);

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

    //FUNCTION FOR SAVE NEW SHARE
    public  function saveshareplaceAction()
    {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $data = $this->params()->fromPost();

        $result = $successModel->saveNewSharePlace($data, $dm);

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

    //FUNCTION FOR LIKE STATUS
    public function savelikestatusAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();
        $result = $successModel->saveLikeStatus($data, $dm);

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

    public function unlikestatusAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $result = $successModel->unlikeStatus($data, $dm);

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

    //FUNCTION FOR Ket ban

    public function addfriendAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $check = $successModel->checkFriend($data['actionUser'], $data['actionLocation'], $dm);
        if($check== null)
        {
            $result = $successModel->sendRequestAddFriend($data, $dm);

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
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }

    }

    public function confirmaddfriendAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $friendModel = new FriendModel();

        $result = $friendModel->confirmaddfriend($data, $dm);

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

    public function unfriendAction()
    {
        $response = $this->getResponse();
        $data = $this->params()->fromPost();
        $dm = $this->getDocumentService();
        $successModel = new SuccessModel();

        $result = $successModel->sendRequestUnFriend($data, $dm);
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

    //FUNCTION FOR SERACH
    public function searchAction()
    {
        $response = $this->getResponse();

        $dm = $this->getDocumentService();
        $data = $this->params()->fromPost();

        $friendModel = new FriendModel();
        $result = $friendModel->getResultSearch($data, $dm);

//        var_dump($result);

        return $response->setContent(\Zend\Json\Json::encode(array(
            'success' => 1,
            'result'  => $result,
        )));
    }

    //FUNCTION FOR CREATE FANPAGE
    public function createfanpageAction()
    {
        $result = $this->getAuthenService();
        if(!$result->hasIdentity())
        {
            return $this->redirect()->toRoute('home');
        }

        $error = null;

        $userID = $this->getUserIdentity()->getUserid();
        $dm = $this->getDocumentService();
        $fanpageModel = new CreatePageModel();

        $request = $this->getRequest();

        if($request->isPost())
        {
            $data = $this->params()->fromPost();
            $rs = $fanpageModel->createNewFanpage($data, $userID, $dm);

            if($rs!=null)
            {
                return $this->redirect()->toUrl('../../fanpage?pageID='.$rs);
            }
            else
            {
                $error = "Tạo mới trang không thành công. Hãy thử lại sau một ít phút nữa!";
            }
        }

        $layoutSetting = $this->layout();
        $layoutSetting->setTemplate('layout/settingpage');

        $result = new ViewModel();
        $result->setTemplate('userpage/userpage/createfanpage');

        $binding = $fanpageModel->getListPageofUser($userID, $dm);

//        var_dump($binding);die();

        $result->setVariables(array(
            'error' => $error,
            'bindingPage' => $binding,
        ));

        return $result;
    }


}