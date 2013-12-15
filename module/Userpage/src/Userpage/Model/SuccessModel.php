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
                ->field('userid')->equals($userid)
                ->field('imagestatus')->equals($imageStatus)
                ->getQuery()
                ->getSingleResult();

            $path = $result->getImageid().'.'.$result->getImagetype();;
            return array('pathAvaUser' => $path);
        }
        else
        {
            return array('pathAvaUser' => $tempPath);
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
    public function getAllContentPrivatePage($userid, $dm)
    {
        $arrayStatusID = array(); //mang nay chua cac ID cua Status IMAGE
        $arrayACTIDhaveCMTID = array(); //mang chua cac actionID trong bang
        $listCMTID = array();

        $arrayCommentContent = array();
        $arrayStatusContent = array();
        $createdtime = array();
        $imageContent = array();
        $actionID = array();

        //get array for statusid
        $temp = $dm->createQueryBuilder('Application\Document\Action')
            ->field('actionuser')->equals($userid)
            ->field('actionlocation')->equals($userid)
            ->sort('createdtime', 'desc')
            ->getQuery()
            ->execute();
        //vong lap co IMG CMT va STT
        foreach($temp as $sttid )
        {

            $statusID = $sttid->getActionType();
            $checkCMT = substr($statusID, 0, 3);
            if($checkCMT != "CMT")
            {
                $arrayStatusID[] = $statusID;
                $createdtime[$statusID] = $sttid->getCreatedTime();
                $actionID[$statusID] = $sttid->getActionid();
            }
            else
            {
                $arrayACTIDhaveCMTID[] = $sttid->getActionid();
            }
        }
        //get status content from array statusid
        foreach($arrayStatusID as $staID)
        {
            $typeofAction = substr($staID, 0, 3);

            if($typeofAction == "STT")
            {
                $temp_status = $dm->createQueryBuilder('Application\Document\Status')
                    ->field('statusid')->equals($staID)
                    ->getQuery()
                    ->getSingleResult();
                $arrayStatusContent[$staID] = $temp_status->getStatusContent();
            }
            elseif($typeofAction == "IMG")
            {

                $doc = $dm->createQueryBuilder('Application\Document\Image')
                    ->field('imageid')->equals($staID)
                    ->getQuery()
                    ->getSingleResult();
                $imageContent[$staID] = $doc->getImageid() .'.'.$doc->getImagetype()."'/>";
            }
        }

        foreach($arrayACTIDhaveCMTID as $actid)
        {
            $temp_cmt = $dm->createQueryBuilder('Application\Document\Comment')
                ->field('actionid')->equals($actid)
                ->getQuery()
                ->getSingleResult();
            $listCMTID[] = $temp_cmt->getCommentid();
        }

        foreach($listCMTID as $CMTID)
        {
            $doc = $dm->createQueryBuilder('Application\Document\Comment')
                ->field('commentid')->equals($CMTID)
                ->getQuery()
                ->getSingleResult();
            $arrayCommentContent[$CMTID] = $doc->getCommentcontent();
        }

        return array(
            'arrStatusID' => $arrayStatusID,
            'arrStatusContent' => $arrayStatusContent,
            'actionTime' => $createdtime,
            'imageContent' =>$imageContent,
            'actionID'   => $actionID,
            'commentContent' => $arrayCommentContent,
            'listCommentID' => $listCMTID,
        );

    }

    public function saveNewStatus($statusContent, $userid, $timeStamp , $documentService )
    {
        $createdTime = $timeStamp;

        //collection('status')
        $statusID = 'STT'.$userid. $createdTime;

        //collection('action')
        $actionID = 'ACT'. $createdTime;
        $actionUser = $actionLocation = $userid;
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

    public function saveNewComment($data, $userid, $documentService)
    {
        $createdTime = $data['timestamp'];
        //value for comment
        $commentID = 'CMT'.$userid.$createdTime;
        $actionID = $data['actionID'];
        $commentContent = $data['cmtContent'];

        //value for action
        $newActionID = 'ACT'.$this->getTimestampNow();
        $actionUser = $actionLocation = $userid;
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
            ->field('userid')->set($userid)
            ->getQuery()
            ->execute();

        //bat dau luu vao bang Action
        $documentService->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($userid)
            ->field('actionlocation')->set($userid)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($actionCreatedTime)
            ->getQuery()
            ->execute();

            return true;
    }



}