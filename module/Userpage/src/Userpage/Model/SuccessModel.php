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

        //BangComment
        $arrayCommentID = array();
        $arrayCommentContent = array();

        $arrayTrueCommentID = array();

        $arrayImagesID = array();

        $cucbo = "";

        $document = $dm->createQueryBuilder('Application\Document\Action')
            ->field('actionuser')->equals($actionUser)
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
                }
                else
                {
                    if($check == "IMG")
                    {
                        $arrayActionType[$actionIDNow] = $document->getActionType();
                        $arrayImagesID[] = $document->getActionType();
                    }
                    else
                    {
                        $arrayActionType[$actionIDNow] = $document->getActionType();
//                        $arrayTrueActionID[] = $document->getActionid();
                    }

                    $arrayTrueActionID[] = $document->getActionid();
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
                ->field('actionuser')->equals($actionUser)
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

        $arrayPathALLIMAGE = array();
        foreach($arrayImagesID as $imageid)
        {
            $document = $dm->createQueryBuilder('Application\Document\Image')
                ->field('imageid')->equals($imageid)
                ->getQuery()
                ->getSingleResult();
            if(isset($document))
            {
                $arrayPathALLIMAGE[$imageid] = $document->getImageid().'.'.$document->getImagetype();
            }
        }

//var_dump($arrayPathALLIMAGE);die();


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
            'arrayPathALLIMAGE'     => $arrayPathALLIMAGE,
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