<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/9/13
 * Time: 10:07 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Model;

use Application\Document\User;
use Application\Document\Image;

class SuccessModel
{
    public function getTimestampNow()
    {
        $date = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $date->getTimestamp();
    }

    //FUNCTON FOR UPDATE-INFO
    public function checkOldPassword($oldPass, $authService)
    {
        $infoUser = $authService->getIdentity();
        if($oldPass == $infoUser->getPassword())
        {
            return true;
        }
        return false;
    }

    public function saveNewPassword($newPass, $userid, $documentService)
    {
        $documentService->createQueryBuilder('Application\Document\User')
            ->update()
            ->multiple(true)
            ->field('password')->set($newPass)
            ->field('userid')->equals($userid)
            ->getQuery()
            ->execute();
        return true;
    }

    public function updateNewInfo($data, $userid, $documentService)
    {
        $documentService->createQueryBuilder('Application\Document\User')
            ->update()
            ->multiple(true)
            ->field('firstname')->set($data['cfirstname'])
            ->field('lastname')->set($data['clastname'])
            ->field('dob')->set($data['cdob'])
            ->field('address')->set($data['caddress'])
            ->field('quote')->set($data['cquote'])
            ->field('userid')->equals($userid)
            ->getQuery()
            ->execute();
        return true;
    }

    public function updateNewAboutme($data, $userid, $documentService)
    {
        $documentService->createQueryBuilder('Application\Document\User')
            ->update()
            ->multiple(true)
            ->field('school')->set($data['school'])
            ->field('work')->set($data['work'])
            ->field('relationship')->set($data['relationship'])
            ->field('userid')->equals($userid)
            ->getQuery()
            ->execute();
        return true;
    }

    public function getPathImageAvatarUser($userid, $dm, $albumType)
    {
        $albumidIfAvailable = $this->checkIsHaveUserAlbumAvatar($userid, $dm, $albumType);

        if($albumType=="AVA")
        {
            $tempPath = 'ava-temp.png';
            $imageStatus="AVA_NOW";
        }
        else
        {
            $tempPath = 'cover-temp.jpg';
            $imageStatus="COV_NOW";
        }
        if(isset($albumidIfAvailable))
        {
            $result = $dm->createQueryBuilder('Application\Document\Image')
                ->field('albumid')->equals($albumidIfAvailable)
                ->field('imagestatus')->equals($imageStatus)
                ->getQuery()
                ->getSingleResult();

            $path = $result->getImageid().'.'.$result->getImagetype();;
            return $path;
        }
        else
        {
            return $tempPath;
        }
    }

    public function resetImageAvatar($userid, $dm, $albumType)
    {
        if($albumType == "AVA")
        {
            $resetAVAorCOV = "AVA_NOW";
            $resetValue = "AVA_OLD";
        }
        else
        {
            $resetAVAorCOV = "COV_NOW";
            $resetValue = "COV_OLD";
        }

        $dm->createQueryBuilder('Application\Document\Image')
            ->update()
            ->multiple(true)
            ->field('imagestatus')->set($resetValue)
            ->field('userid')->equals($userid)
            ->field('imagestatus')->equals($resetAVAorCOV)
            ->getQuery()
            ->execute();
        return true;
    }

    public function checkIsHaveUserAlbumAvatar($userid, $dm, $albumType)
    {
        $albumid = 'ALB'.$userid.$albumType;
        $result = $dm->getRepository('Application\Document\Album')->findOneBy(array('albumid' => $albumid));
        if(isset($result))
        {
            $value = $result->getAlbumid();
            return $value;
        }
        else
        {
            return null;
        }
    }

    //FUNCTION FOR TRANG CA NHAN
    public function getAllContentPrivatePage($actionUser, $actionLocation, $dm)
    {
        //danh sach cac action cua user
        $listAllActionID = array();

        $arrayTrueActionID = array();

        //bang Action
        $arrayActionUser = array();
        $arrayActionLocation = array();
        $arrayActionCreatedTime = array();
        $arrayActionType = array(); //chi GOM CO STATUS va IMAGE

        //BangStatus
        $arrayStatusContent = array();

        //bang Video
        $arrayListVID = array();

        //Test array userid va array userlocation
        $arrayActuserAclocation = array();

        //bang Place
        $arrayListPLA = array();

        //BangComment
        $arrayCommentID = array();
        $arrayCommentContent = array();

        //Bang LIKE
        //@TODO:
        $arrayListLike = array();

        $arrayTrueCommentID = array();

        $arrayImagesID = array();

        $cucbo = "";

        $document = $dm->createQueryBuilder('Application\Document\Action')
//            ->field('actionuser')->equals($actionUser)
            ->field('actionlocation')->equals($actionLocation)
            ->sort('createdtime', 'desc')
            ->getQuery()
            ->execute();
        if(isset($document))
        {
            foreach($document as $actionid)
            {
                $listAllActionID[] = $actionid->getActionid();
            }
        }
        //lay toan bo thong tin bang action dua vao ACTIONID
        foreach($listAllActionID as $actID)
        {
            $document = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionid')->equals($actID)
                ->sort('createdtime', 'desc')
                ->getQuery()
                ->getSingleResult();

            if(isset($document))
            {
                $arrayActionUser[$actID] = $document->getActionUser();
                $arrayActionLocation[$actID] = $document->getActionLocation();
                $arrayActionCreatedTime[$actID] = $document->getCreatedTime();
                $actionIDNow = $document->getActionid();
                $check = substr($document->getActionType(), 0, 3);
                if($check == "CMT")
                {
                    $commentID = $document->getActionType();
                    $cucbo = $commentID;
                    $arrayCommentID[] = $commentID;

                    $arrayActuserAclocation[$document->getActionid()] =array(
                        'actionUser' => $document->getActionUser(),
                        'actionLocation' =>$document->getActionLocation(),
                    );
                }
                elseif($check == "LIK")
                {
                    $arrayListLike[] = $document->getActionid();
                }
                else
                {
                    if($check == "IMG")
                    {//danh sach IMG
//                        $arrayActionType[$actionIDNow] = $document->getActionType();
                        $arrayImagesID[] = $document->getActionType();
                        $arrayTrueActionID[] = $document->getActionid();

                        $arrayActuserAclocation[$document->getActionid()] =array(
                            'actionUser' => $document->getActionUser(),
                            'actionLocation' =>$document->getActionLocation(),
                        );
                    }
                    elseif($check == "STT")
                    {//danh sach status id
//                        $arrayActionType[$actionIDNow] = $document->getActionType();
                        $arrayTrueActionID[] = $document->getActionid();

                        $arrayActuserAclocation[$document->getActionid()] =array(
                            'actionUser' => $document->getActionUser(),
                            'actionLocation' =>$document->getActionLocation(),
                        );

                    }
                    elseif($check == "PLA")
                    { //danh sach place share
                        $arrayListPLA[] = $document->getActionType();
                        $arrayTrueActionID[] = $document->getActionid();

                        $arrayActuserAclocation[$document->getActionid()] =array(
                            'actionUser' => $document->getActionUser(),
                            'actionLocation' =>$document->getActionLocation(),
                        );
                    }
                    else
                    { //danh sach video
                        $arrayTrueActionID[] = $document->getActionid();
                        $arrayListVID[] = $document->getActionType();

                        $arrayActuserAclocation[$document->getActionid()] =array(
                            'actionUser' => $document->getActionUser(),
                            'actionLocation' =>$document->getActionLocation(),
                        );
                    }
                    $arrayActionType[$actionIDNow] = $document->getActionType();

                }
                $document = $dm->createQueryBuilder('Application\Document\Comment')
                    ->field('actionid')->equals($actID)
                    ->field('commentid')->equals($cucbo)
                    ->getQuery()
                    ->getSingleResult();
                if(isset($document))
                {
                    $actionIDChange = $document->getActionid();
                    $arrayCommentContent[$actionIDChange][$cucbo] = $document->getCommentContent();
                }
            }
        }

//        var_dump($arrayTrueActionID);die();

        foreach($arrayTrueActionID as $actionID)
        {
            foreach($arrayCommentID as $commentID)
            {
                $document = $dm->createQueryBuilder('Application\Document\Comment')
                    ->field('actionid')->equals($actionID)
                    ->field('commentid')->equals($commentID)
                    ->getQuery()
                    ->getSingleResult();

                if(isset($document))
                {
                    $arrayTrueCommentID[$document->getActionid()][$document->getCommentid()] = $document->getCommentContent();
                }
            }
        }

        //Lay toan bo thong tin bang STATUS dua vao ACTIONTYPE
        foreach($arrayActionType as $statusID)
        {
            $document = $dm->createQueryBuilder('Application\Document\Status')
                ->field('statusid')->equals($statusID)
                ->getQuery()
                ->getSingleResult();

            if(isset($document))
            {
                $arrayStatusContent[$statusID] = $document->getStatusContent();
            }
        }

//        $arrayCommentContent SAI
        $allCommentID = array();

        foreach($listAllActionID as $actionID)
        {
            $document = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionid')->equals($actionID)
//                ->field('actionuser')->equals($actionUser)
                ->field('actionlocation')->equals($actionLocation)
                ->sort('createdtime', 'asc')
                ->getQuery()
                ->getSingleResult();
            if(isset($document))
            {
                $value = $document->getActionType();
                if(substr($value, 0, 3) == "CMT")
                {
                    $allCommentID[$value] = $value;
                }
            }
        }

//        var_dump($allCommentID); die();

        $arrayPathALLIMAGE = array();
        foreach($arrayImagesID as $imageid)
        {
            $document = $dm->createQueryBuilder('Application\Document\Image')
                ->field('imageid')->equals($imageid)
                ->getQuery()
                ->getSingleResult();
            if(isset($document))
            {
                $arrayPathALLIMAGE[$imageid] = array(
                        'path' => $document->getImageid().'.'.$document->getImagetype(),
                        'content'=>  $document->getImagedescription(),
                    );

//                $arrayPathALLIMAGE[$imageid] = $document->getImageid().'.'.$document->getImagetype();
            }
        }

        $arrayPathALLVIDEO = array();
        foreach($arrayListVID as $videoid)
        {
            $document = $dm->createQueryBuilder('Application\Document\Video')
                ->field('videoid')->equals($videoid)
                ->getQuery()
                ->getSingleResult();
            if(isset($document))
            {
                $arrayPathALLVIDEO[$videoid] = array(
                    'path' => $document->getVideoid().'.'.$document->getVideotype(),
                    'content' =>$document->getVideodescription(),
                );
            }
        }



        $arrayALLSharePlace = array();
        foreach($arrayListPLA as $placeid)
        {
            $document = $dm->createQueryBuilder('Application\Document\Place')
                ->field('placeid')->equals($placeid)
                ->getQuery()
                ->getSingleResult();

            if(isset($document))
            {
                $imageIDTemp = $document->getImageid();
                $doc = $dm->createQueryBuilder('Application\Document\Image')
                    ->field('imageid')->equals($imageIDTemp)
                    ->getQuery()
                    ->getSingleResult();

                $arrayALLSharePlace[$placeid] = array(
                    'placeName' => $document->getPlacename(),
                    'placeDescription' => $document->getPlacedescription(),
                    'hashTag'   => $document->getHashtag(),
                    'pathImage' => $imageIDTemp.'.'.$doc->getImagetype(),
                );
            }
        }




//var_dump($arrayTrueActionID);die();


        return array(
            'arrayTrueActionID'        => $arrayTrueActionID,
            //Action
            'arrayActionUser'        => $arrayActionUser,
            'arrayActionLocation'    => $arrayActionLocation,
            'arrayActionType'        => $arrayActionType,
            'arrayActionCreatedTime' => $arrayActionCreatedTime,
            //Status
            'arrayStatusContent'     => $arrayStatusContent,
            //Bang Comment
            'arrayCommentContent'    => $arrayTrueCommentID,
            'allCommentID'           => $allCommentID,
            'arrayPathALLIMAGE'      => $arrayPathALLIMAGE,
            //Bang video
            'arrayPathALLVIDEO'     => $arrayPathALLVIDEO,
            //bang Place
            'arrayALLSharePlace'   => $arrayALLSharePlace,

            //test
            'arrayActuserAclocation' =>$arrayActuserAclocation
        );


    }

    public function saveNewStatus($statusContent, $userID, $userlocationID, $timeStamp , $documentService )
    {
        $createdTime = $timeStamp;

        //collection('status')
        $statusID = 'STT'.$userID. $createdTime;

        //collection('action')
        $actionID = 'ACT'. $createdTime;
        $actionUser = $userID;
        $actionLocation = $userlocationID;
        $actionType = $statusID;

        //THem mot bang moi vao Sttus
        $documentService->createQueryBuilder('Application\Document\Status')
            ->insert()
            ->field('statusid')->set($statusID)
            ->field('statuscontent')->set($statusContent)
            ->getQuery()
            ->execute();
        //Them mot truong moi vao bang Action
        $documentService->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

       if(isset($documentService))
           return true;
        else
            return false;
    }

    public function saveNewComment($data, $documentService)
    {
        $createdTime = $data['timeCreatedComment'];
        $actUserID = $data['actionUser'];
        $actLocationID = $data['actionLocation'];
        //value for comment
        $commentID = 'CMT'.$actUserID.$createdTime;
        $actionID =  $data['actionStatusSave'];
        $commentContent = $data['cmtContent'];

        //value for action
        $newActionID = 'ACT'.$createdTime;
        $actionUser = $actUserID;
        $actionLocation = $actLocationID;
        $actionType = $commentID;

        //them vao bang action
        $documentService->createQueryBuilder('Application\Document\Comment')
            ->insert()
            ->field('commentid')->set($commentID)
            ->field('actionid')->set($actionID)
            ->field('commentcontent')->set($commentContent)
            ->getQuery()
            ->execute();

        //them vao bang comment
        $documentService->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($newActionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        if(isset($documentService))
            return true;
        else
            return false;

    }

    //FOR UPDATE AVATAR
    public function saveNewImageAvatar($userid, $createdTime, $documentService, $imageType, $albumType)
    {
        $timeNow = $this->getTimestampNow();

        $albumidAvai = $this->checkIsHaveUserAlbumAvatar($userid, $documentService, $albumType);

        //Bang Image
        $imageID = "IMG".$userid.$createdTime.$albumType;
        $imageAlbumID = "";
        if($albumType == "AVA")
        {
            $imageStatus = "AVA_NOW";
        }
        else
        {
            $imageStatus = "COV_NOW";
        }

        //Bang action
        $actionID = "ACT".$timeNow;
        $actionType = $imageID;
        $actionCreatedTime = $timeNow;

        if($albumidAvai != null)
        {
            //Truong hop da co AlbumAva san trong bang Album
            $imageAlbumID = $albumidAvai;

            //reset hinh dang la hinh avatar
            $this->resetImageAvatar($userid, $documentService, $albumType);
        }
        else
        {
            //Truong hop chua co album Avatar trong bang Album
            //Bat dau tao moi albumid cho album avatar cua user

            //bat dau luu vao  Bang Album
            $albumID = 'ALB'.$userid.$albumType;
            $albumUserid = $userid;

            //sua lai image album id
            $imageAlbumID = $albumID;

            $documentService->createQueryBuilder('Application\Document\Album')
                ->insert()
                ->field('albumid')->set($albumID)
                ->field('userid')->set($albumUserid)
                ->getQuery()
                ->execute();
        }

        //bat dau luu vao bang Image
        $documentService->createQueryBuilder('Application\Document\Image')
            ->insert()
            ->field('imageid')->set($imageID)
            ->field('albumid')->set($imageAlbumID)
            ->field('imagestatus')->set($imageStatus)
            ->field('imagetype')->set($imageType)
//            ->field('userid')->set($userid)
            ->getQuery()
            ->execute();

        //bat dau luu vao bang Action
        $doc = $documentService->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($userid)
            ->field('actionlocation')->set($userid)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($actionCreatedTime)
            ->getQuery()
            ->execute();

        if(isset($doc))
        {
            return true;
        }
        else
            return false;
    }

    //FOR POST NEW IMAGE
    public function saveNewImageNormal($userid, $createdTime, $descript, $imageType, $dm)
    {
        $checkAlbumNOR = $this->checkIsHaveUserAlbumAvatar($userid,$dm,'NOR');

        //bang Image
        $imageid = "IMG".$userid.$createdTime."NOR";
        $imageStatus = "NOR";
        $fullImage = $imageid.'.'.$imageType;

        //bang action
        $actionid = 'ACT'.$createdTime;
        $actionUser = $actionLocation = $userid;
        $actionType = $imageid;

        if($checkAlbumNOR != null)
        {
            $albumID = $checkAlbumNOR;

            $document = $dm->createQueryBuilder('Application\Document\Image')
                ->insert()
                ->field('imageid')->set($imageid)
                ->field('albumid')->set($albumID)
                ->field('imagedescription')->set($descript)
                ->field('imagestatus')->set($imageStatus)
                ->field('imagetype')->set($imageType)
                ->getQuery()
                ->execute();

            if(isset($document))
            {
                $document=$dm->createQueryBuilder('Application\Document\Action')
                    ->insert()
                    ->field('actionid')->set($actionid)
                    ->field('actionuser')->set($actionUser)
                    ->field('actionlocation')->set($actionLocation)
                    ->field('actiontype')->set($actionType)
                    ->field('createdtime')->set($createdTime)
                    ->getQuery()
                    ->execute();

                if(isset($document))
                    return $fullImage;
                else
                    return null;
            }
            else
                return null;

        }
        else
        {
            //bang Album
            $albumID = "ALB".$userid."NOR";

            $document = $dm->createQueryBuilder('Application\Document\Album')
                ->insert()
                ->field('albumid')->set($albumID)
                ->field('userid')->set($userid)
                ->getQuery()
                ->execute();


            $document = $dm->createQueryBuilder('Application\Document\Image')
                ->insert()
                ->field('imageid')->set($imageid)
                ->field('albumid')->set($albumID)
                ->field('imagedescription')->set($descript)
                ->field('imagestatus')->set($imageStatus)
                ->field('imagetype')->set($imageType)
                ->getQuery()
                ->execute();

            $document=$dm->createQueryBuilder('Application\Document\Action')
                ->insert()
                ->field('actionid')->set($actionid)
                ->field('actionuser')->set($actionUser)
                ->field('actionlocation')->set($actionLocation)
                ->field('actiontype')->set($actionType)
                ->field('createdtime')->set($createdTime)
                ->getQuery()
                ->execute();
            if(isset($document))
                return $fullImage;
            else
                return null;

        }
    }

    //FUNCTION FOR SAVE VIDEO
    public function saveNewVideo($dm, $data)
    {
        $createdTime = $data['createdTime'];
        $userid = $data['userid'];
        //Bang Video
        $videoID = 'VID'.$userid.$createdTime;
        $videoDescription = $data['descript'];
        $videoType = $data['videoType'];
        $videoType = substr($videoType, -3, 3);

        //Bang Action
        $actionID = 'ACT'.$createdTime;
        $actionUser =  $actionLocation= $userid;
        $actionType = $videoID;
        $actionCreatedTime = $createdTime;

        //Bang Album
        $albumID = 'ALB'.$userid.'VID';
        $albumUserID = $userid;

        $checkAlbum = $this->checkIsHaveUserAlbumAvatar($userid, $dm, "VID");

        if($checkAlbum == null)
        {
            $document = $dm->createQueryBuilder('Application\Document\Album')
                ->insert()
                ->field('albumid')->set($albumID)
                ->field('userid')->set($userid)
                ->getQuery()
                ->execute();

        }

        $document=$dm->createQueryBuilder('Application\Document\Video')
            ->insert()
            ->field('videoid')->set($videoID)
            ->field('albumid')->set($albumID)
            ->field('videodescription')->set($videoDescription)
            ->field('videotype')->set($videoType)
            ->getQuery()
            ->execute();

        $document=$dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($actionCreatedTime)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;
    }

    //FUCTION FOR SAVE NEW SHARE PLACE
    public function saveNewSharePlace($data, $dm)
    {
        $actUserID = $data['actionUser'];
        $actLocationID = $data['actionLocation'];
        $createdTime = $data['createdTime'];
        $extImage = $data['imageType'];
        if(substr($extImage, -4,1) == ".")
        {
            $extImage = substr($extImage, -3,3);
        }
        else
        {
            $extImage = substr($extImage, -4,4);
        }
        //Bang Album
        $albumID = "ALB".$actUserID."NOR";
        $albumUserid = $actUserID;

        //Bang Image
        $imageID = "IMG".$actUserID.$createdTime."NOR";
        $imageAlbumID = $albumID;
        $imageStatus = "NOR";
        $imageType = $extImage;

        //Bang PLACE
        $placeID = "PLA".$actUserID.$createdTime;
        $placeName = $data['placeName'];
        $placeDescription = $data['descript'];
        $placeImageID = $imageID;
        $hashTag = $data['hashTag'];

        //bang Action
        $actionID = "ACT".$createdTime;
        $actionUser = $actUserID;
        $actionLocation = $actLocationID;
        $actionType = $placeID;

        $checkAlbum = $this->checkIsHaveUserAlbumAvatar($actUserID, $dm, "NOR");

        if($checkAlbum == null)
        {
            $document = $dm->createQueryBuilder('Application\Document\Album')
                ->insert()
                ->field('albumid')->set($albumID)
                ->field('userid')->set($albumUserid)
                ->getQuery()
                ->execute();
        }

        $document=$dm->createQueryBuilder('Application\Document\Image')
            ->insert()
            ->field('imageid')->set($imageID)
            ->field('albumid')->set($imageAlbumID)
            ->field('imagestatus')->set($imageStatus)
            ->field('imagetype')->set($imageType)
            ->getQuery()
            ->execute();

        $document=$dm->createQueryBuilder('Application\Document\Place')
            ->insert()
            ->field('placeid')->set($placeID)
            ->field('placename')->set($placeName)
            ->field('placedescription')->set($placeDescription)
            ->field('imageid')->set($placeImageID)
            ->field('hashtag')->set($hashTag)
            ->getQuery()
            ->execute();

        $document=$dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;
    }

    //FUNCTION FOR SAVE LIKE STATUS
    public function saveLikeStatus($data, $dm)
    {
        $createdTime = $data['timeStamp'];
        $userid = $data['userid'];
        //Bang Like
        $likeID = "LIK".$userid.$createdTime;
        $likeAction = $data['actionid'];
        //bang Action
        $actionID = "ACT".$createdTime;
        $actionUser = $actionLocation = $userid;
        $actionType = $likeID;

        $document=$dm->createQueryBuilder('Application\Document\Like')
            ->insert()
            ->field('likeid')->set($likeID)
            ->field('actionid')->set($likeAction)
            ->getQuery()
            ->execute();

        $document=$dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;

    }

    //FUNCTION FOR Load all private Infomation
    public function getPrivateInfomationUser($userid, $dm)
    {
        $document = $dm->getRepository('Application\Document\User')->findOneBy(array('userid' => $userid));
        if(isset($document))
        {
            return $document;
        }
        else
            return null;
    }

    public function getInfoUserByID($dm)
    {
        $result = array();

        $qb = $dm->createQueryBuilder('Application\Document\User')
            ->eagerCursor(true);
        $query = $qb->getQuery();
        $cursor = $query->execute();

        foreach($cursor as $doc)
        {
            $pathAva = $this->getPathImageAvatarUser($doc->getUserid(), $dm, "AVA" );
            $result[$doc->getUserid()] = array(
                'fullname' => $doc->getLastname().' '.$doc->getFirstname(),
                'pathAvatar'       => $pathAva,
            );
        }

        return $result;
    }

    public function getInfoUserbyActionType($dm)
    {
        $result = array();

        $qb = $dm->createQueryBuilder('Application\Document\Action')
            ->eagerCursor(true);
        $query = $qb->getQuery();
        $cursor = $query->execute();

        foreach($cursor as $doc)
        {
            $pathAva = $this->getPathImageAvatarUser($doc->getActionUser(), $dm, "AVA" );
            $actionType = $doc->getActionType();
            $fullname = $this->getPrivateInfomationUser($doc->getActionUser(),$dm);
            $result[$actionType] = array(
                'fullname' => $fullname->getLastname().' '.$fullname->getFirstname(),
                'pathAvatar'       => $pathAva,
            );
        }

        return $result;
    }

    //FUNCTION FOR FRIEND

    public function getRequestFriend($actionUser, $actionLocation, $dm)
    {
        $doc1=$dm->createQueryBuilder('Application\Document\Friend')

            ->field('frienduserrecieve')->equals($actionUser)
            ->field('friendstatus')->equals("SENT")
            ->getQuery()
            ->execute();


        if(isset($doc1))
        {
            return $doc1->count();
        }
        else
            return null;

    }

    public function saveNewFriend($data, $dm)
    {

    }

    public function sendRequestAddFriend($data, $dm)
    {
        //bang friend
        $friendUserSend = $data['actionUser'];
        $friendUserRecieve = $data['actionLocation'];
        $friendStatus = $data['friendStatus'];

        $document=$dm->createQueryBuilder('Application\Document\Friend')
            ->insert()
            ->field('friendusersend')->set($friendUserSend)
            ->field('frienduserrecieve')->set($friendUserRecieve)
            ->field('friendstatus')->set($friendStatus)
            ->getQuery()
            ->execute();

        if(isset($document))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function sendRequestUnFriend($data, $dm)
    {
        $friendUserSend = $data['actionUser'];
        $friendUserRecieve = $data['actionLocation'];
        $friendStatus = $data['friendStatus'];

        $document = $dm -> createQueryBuilder('Application\Document\Friend')
            ->remove()
            ->field('friendusersend')->equals($friendUserSend)
            ->field('frienduserrecieve')->equals($friendUserRecieve)
            ->field('friendstatus')->equals($friendStatus)
            ->getQuery()
            ->execute();

        $document2 = $dm -> createQueryBuilder('Application\Document\Friend')
            ->remove()
            ->field('friendusersend')->equals($friendUserRecieve)
            ->field('frienduserrecieve')->equals($friendUserSend)
            ->field('friendstatus')->equals($friendStatus)
            ->getQuery()
            ->execute();

        if(isset($document))
        {
            return true;
        }
        elseif(isset($document2))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    public function checkFriend($userSend, $userRecieve, $dm)
    {
        $document=$dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendusersend')   ->equals($userSend)
            ->field('frienduserrecieve')->equals($userRecieve)
            ->getQuery()
            ->getSingleResult();

        $document2=$dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendusersend')   ->equals($userRecieve)
            ->field('frienduserrecieve')->equals($userSend)
            ->getQuery()
            ->getSingleResult();

        if(isset($document))
        {
            return $document->getFriendstatus();
        }
        elseif(isset($document2))
        {
            return $document2->getFriendstatus();
        }
        else
            return null;

    }




}