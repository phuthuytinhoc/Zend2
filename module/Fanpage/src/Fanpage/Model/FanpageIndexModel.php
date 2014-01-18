<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/11/14
 * Time: 10:29 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Fanpage\Model;

use Zend\Config\Reader\Xml;
use Application\Document;

class FanpageIndexModel
{
    public function loadStatusUseronPageWall($pageID, $dm)
    {
        $binding = null;

        $action = $dm->createQueryBuilder('Application\Document\Action')
            ->select()
            ->field('actionlocation')->equals($pageID)
            ->field('actionuser')->notEqual($pageID)
            ->getQuery()
            ->execute();

        if(isset($action))
        {
            foreach($action as $node)
            {
                $actionType = $node->getActiontype();
                if(substr($actionType, 0, 3) == "STT")
                {
                    $userID = $node->getActionuser();
                    $albumID = 'ALB'.$userID.'AVA';
                    $pathAva = 'ava-temp.png';
                    $status = $dm->createQueryBuilder('Application\Document\Status')
                        ->select()
                        ->field('statusid')->equals($actionType)
                        ->getQuery()
                        ->getSingleResult();
                    $user = $dm->createQueryBuilder('Application\Document\User')
                        ->select()
                        ->field('userid')->equals($userID)
                        ->getQuery()
                        ->getSingleResult();
                    $imageAva = $dm->createQueryBuilder('Application\Document\Image')
                        ->select()
                        ->field('albumid')->equals($albumID)
                        ->field('imagestatus')->equals('AVA_NOW')
                        ->getQuery()
                        ->getSingleResult();


                    if(isset($status) && isset($user))
                    {
                        $statusContent = $status->getStatuscontent();
                        $username = $user->getLastname().' '.$user->getFirstname();
                    }
                    if(isset($imageAva))
                    {
                        $pathAva = $imageAva->getImageid().'.'.$imageAva->getImagetype();
                    }

                    $binding[] = array(
                        'statusid'      => $actionType,
                        'statuscontent' => $statusContent,
                        'createdtime'   => $node->getCreatedtime(),
                        'actionuser'    => $node->getActionuser(),
                        'username'      => $username,
                        'pathava'       => $pathAva,
                    );
                }
            }
        }
        return $binding;
    }

    public function loadCommentbyActionID($data, $dm)
    {
        $binding = null;
        $listCMTID = null;
//        $pageID   = $data['actionLocation'];
        $actionID = $data['actionID'];

        $comment = $dm->createQueryBuilder('Application\Document\Comment')
            ->select()
            ->field('actionid')->equals($actionID)
            ->getQuery()
            ->execute();

        if(isset($comment))
        {
            $i = 0;
            foreach($comment as $node)
            {
                $listCMTID[] = $node->getCommentid();
                $actionType = $node->getCommentid();

                $action = $dm->createQueryBuilder('Application\Document\Action')
                    ->select()
                    ->field('actiontype')->equals($actionType)
                    ->getQuery()
                    ->getSingleResult();

                if(isset($action))
                {
                    $actionUser = $action->getActionuser();
                    $albID = 'ALB'.$actionUser.'AVA';

                    $imgAva = $dm->createQueryBuilder('Application\Document\Image')
                        ->select()
                        ->field('albumid')->equals($albID)
                        ->field('imagestatus')->equals('AVA_NOW')
                        ->getQuery()
                        ->getSingleResult();

                    if(isset($imgAva))
                    {
                        $avaUser = $imgAva->getImageid().'.'.$imgAva->getImagetype();
                    }

                    if(substr($actionUser, 0, 4) == 'user')
                    {
                        $avaUser = 'ava-temp.png';
                        $user = $dm->createQueryBuilder('Application\Document\User')
                            ->select()
                            ->field('userid')->equals($actionUser)
                            ->getQuery()
                            ->getSingleResult();
                        if(isset($user))
                        {
                            $nameUser = $user->getLastname().' '.$user->getFirstname();
                        }
                    }
                    else
                    {
                        $avaUser = 'ava-page-temp.png';
                        $page = $dm->createQueryBuilder('Application\Document\Fanpage')
                            ->select()
                            ->field('pageid')->equals($actionUser)
                            ->getQuery()
                            ->getSingleResult();
                        if(isset($page))
                        {
                            $nameUser = $page->getPagename();
                        }
                    }

                    $binding[] = array(
                        'count'          => $i,
                        'commentid'      => $actionType,
                        'commentcontent' => $node->getCommentcontent(),
                        'actionuser'     => $actionUser,
                        'nameuser'       => $nameUser,
                        'avauser'        => $avaUser,
                        'createdtime'    => substr($actionType, -10, 10),
                    );

                    $i++;

                }

            }
        }

        return $binding;
    }

    public function getPathAvatarofPageorUser($ID, $dm)
    {
        $albumID = 'ALB'.$ID.'AVA';
        $imageID = 'ava-temp.png';
        $arr = array();
        $imageAva = $dm->createQueryBuilder('Application\Document\Image')
            ->select()
            ->field('albumid')->equals($albumID)
            ->field('imagestatus')->equals('AVA_NOW')
            ->getQuery()
            ->getSingleResult();

        if(isset($imageAva))
        {
            $imageID = $imageAva->getImageid().'.'.$imageAva->getImagetype();

        }

        if(substr($ID, 0, 4) == 'user')
        {
            $user = $dm->createQueryBuilder('Application\Document\User')
                ->select()
                ->field('userid')->equals($ID)
                ->getQuery()
                ->getSingleResult();

            if(isset($user)){
                $arr['name'] = $user->getLastname().' '.$user->getFirstname();
            }

        }

        $arr['pathAva'] = $imageID;
        return $arr;
    }

    public function insertNewComment($data, $dm)
    {
        //action
        $createTime = $data['createdTime'];
        $actionID   = 'ACT'.$createTime;
        $actionUser   = $data['actionUser'];
        $actionLocation   = $data['actionLocation'];
        $actionType = 'CMT'.$actionUser.$createTime;

        //comment
        $cmtID       = $actionType;
        $cmtActionID = $data['actionID'];
        $cmtContent  = $data['cmtContent'];

        $action = $dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createTime)
            ->getQuery()
            ->execute();

        $comment = $dm->createQueryBuilder('Application\Document\Comment')
            ->insert()
            ->field('commentid')->set($cmtID)
            ->field('actionid')->set($cmtActionID)
            ->field('commentcontent')->set($cmtContent)
            ->getQuery()
            ->execute();

        if(isset($action) && isset($comment)){
            return true;
        }else{
            return false;
        }
    }

    public function getDataIndexFanpage($pageID, $dm)
    {
        $bindingIndex = null;
        $action = $dm->createQueryBuilder('Application\Document\Action')
            ->select()
            ->field('actionuser')->equals($pageID)
            ->field('actionlocation')->equals($pageID)
            ->getQuery()
            ->execute();

        if(isset($action))
        {
            foreach($action as $node)
            {
                $arr = array();
                $actType = $node->getActionType();
                $check = substr($actType, 0, 3);
                if($check == "STT")
                {
                    $status = $dm->createQueryBuilder('Application\Document\Status')
                        ->select()
                        ->field('statusid')->equals($actType)
                        ->getQuery()
                        ->getSingleResult();
                    if(isset($status))
                    {
                        $arr = array(
                            'id'     => $status->getStatusid(),
                            'source' => $status->getStatusContent(),
                            'type'   => 'addStatus',
                        );
                    }

                }
               elseif($check == "IMG")
                {
                    $image = $dm->createQueryBuilder('Application\Document\Image')
                        ->select()
                        ->field('imageid')->equals($actType)
                        ->getQuery()
                        ->getSingleResult();

                    if(isset($image))
                    {
                        $imgID = $image->getImageid();
                        $typeImage = substr($imgID, -3, 3);
                        if($typeImage == 'SLI'){
                            $typeImage = "changeSlide";
                        }elseif($typeImage == "NOR"){
                            $typeImage = "addImage";
                        }elseif($typeImage == "OGO"){
                            $typeImage = "changeLogo";
                        }elseif($typeImage == "AVA"){
                            $typeImage = "changeAva";
                        }

                        $arr = array(
                            'id'     => $imgID,
                            'source' => $imgID.'.'.$image->getImagetype(),
                            'descript' => $image->getImagedescription(),
                            'type'   => $typeImage,
                        );
//                        var_dump($arr);die();
                    }
                }

                if($arr != null)
                {
                    $bindingIndex[]   =  array(
                        'actionid'    => $node->getActionid(),
                        'createdtime' => $node->getCreatedTime(),
                        'content'     => $arr,
                    );
                }

            }
        }

        return $bindingIndex;
    }

    public function insertStatusPage($data, $dm)
    {
        $createdTime = $data['createdTime'];
        $actionID = 'ACT'.$createdTime;
        $actionUser = $data['userNow'];
        $actionLocation = $data['pageID'];
        $actionType = 'STT'.$actionUser.$createdTime;
        //status
        $statusID = $actionType;
        $statusContent = $data['statusContent'];

        $action = $dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set($actionID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        $status = $dm->createQueryBuilder('Application\Document\Status')
            ->insert()
            ->field('statusid')->set($statusID)
            ->field('statuscontent')->set($statusContent)
            ->getQuery()
            ->execute();

        if(isset($action) && isset($status)){
            return true;
        }else{
            return false;
        }
    }

    public function checkExistsPage($pageID, $dm)
    {
        $check = false;

        $page = $dm->createQueryBuilder('Application\Document\Fanpage')
            ->select()
            ->field('pageid')->equals($pageID)
            ->getQuery()
            ->getSingleResult();

        if(isset($page))
        {
            $check = true;
        }
        return $check;
    }

    public function checkUserOwnerPage($userID, $pageID, $dm)
    {
        $check = null;
        $page = $dm->createQueryBuilder('Application\Document\FanpageManage')
            ->select()
            ->field('pageid')->equals($pageID)
            ->field('userid')->equals($userID)
            ->getQuery()
            ->getSingleResult();

        if(isset($page))
        {
            $check = $page->getPageuserstatus();
        }

        return $check;
    }

    public function bindingDataPageIndex($userID, $pageID, $dm)
    {
        $bindingInfo = null;
        $bindingImage = null;

        $page = $dm->createQueryBuilder('Application\Document\Fanpage')
            ->select()
            ->field('pageid')->equals($pageID)
//            ->field('userid')->equals($userID)
            ->getQuery()
            ->getSingleResult();

        $countLike = $dm->createQueryBuilder('Application\Document\FanpageManage')
            ->select()
            ->field('pageid')->equals($pageID)
//            ->field('userid')->equals($userID)
            ->field('pageuserstatus')->equals('LIKE')
            ->getQuery()
            ->execute()->count();

        if(isset($page))
        {
            $bindingInfo = array(
                'pageName' => $page->getPagename(),
                'pageDes'  => $page->getPagedescription(),
                'pageMapInfo' => $page->getPagemapinfo(),
                'pageLongt'=> $page->getPagelongtitude(),
                'pageLat'  => $page->getPagelatitude(),
                'pageOwner'=> $page->getUserid(),
                'pageID'   => $page->getPageid(),
                'pageLike' => $countLike,
            );
        }

        $albumLogo = 'ALB'.$pageID.'LOGO';
        $albumAva  = 'ALB'.$pageID.'AVA';
        $albumSlide = 'ALB'.$pageID.'SLI';
        $avaTemp   = 'ava-page-temp.png';
        $logoTemp   = 'logo-temp.png';
        $slideShowTemp = array('slide-temp-1.jpg','slide-temp-2.jpg','slide-temp-3.jpg');

        $imageLogo = $dm->createQueryBuilder('Application\Document\Image')
            ->select()
            ->field('albumid')->equals($albumLogo)
            ->field('imagestatus')->equals('LOGO_NOW')
            ->getQuery()
            ->getSingleResult();

        if(isset($imageLogo))
        {
            $logoTemp = $imageLogo->getImageid().'.'.$imageLogo->getImagetype();
        }

        $imageAva = $dm->createQueryBuilder('Application\Document\Image')
            ->select()
            ->field('albumid')->equals($albumAva)
            ->field('imagestatus')->equals('AVA_NOW')
            ->getQuery()
            ->getSingleResult();

        if(isset($imageAva))
        {
            $avaTemp = $imageAva->getImageid().'.'.$imageAva->getImagetype();
        }

        $slideShow = $dm->createQueryBuilder('Application\Document\Image')
            ->select()
            ->field('albumid')->equals($albumSlide)
            ->field('imagestatus')->equals('SLIDESHOW')
            ->getQuery()
            ->execute();

        if(isset($slideShow))
        {
            foreach($slideShow as $node)
            {
                $slideShowTemp[] =  $node->getImageid().'.'.$node->getImagetype();
            }
        }

        $bindingImage = array(
            'imageAva'  => $avaTemp,
            'imageLogo' => $logoTemp,
            'imageSlideShow' => $slideShowTemp,
        );

//        var_dump($slideShowTemp);die();

        return array(
            'bindingInfo'  => $bindingInfo,
            'bindingImage' => $bindingImage,
        );
    }

    public static function getDataImageBindingEffect($userID, $pageID, $dm)
    {
        $albumMainID = 'ALB'.$pageID.'MAIN';

        $name = null;
        $description = null;
        $photosArr = null;
        $listAlbumID = null;
        $extraArray = null;

        $doc = $dm->createQueryBuilder('Application\Document\Album')
            ->select()
            ->field('albumid')->equals($albumMainID)
            ->getQuery()
            ->getSingleResult();
        if(isset($doc))
        {
            $name = $doc->getAlbumname();
            $description = $doc->getAlbumdescription();
        }
        else
            return $extraArray;

        $albums = $dm->createQueryBuilder('Application\Document\Album')
            ->select()
            ->field('userid')->equals($pageID)
            ->getQuery()
            ->execute();

        if(isset($albums))
        {
            foreach($albums as $node)
            {
                $idTemp = $node->getAlbumid();
                if(substr($idTemp, -17, 7) == 'SUBMAIN')
                {
                    $listAlbumID[] = array(
                        'id' => $idTemp,
                        'albumName'=> $node->getAlbumname(),
                        'albumDescript' =>$node->getAlbumdescription(),
                        'albumLat' => $node->getAlbumlatitude(),
                        'albumLong'=> $node->getAlbumlongtitude(),

                    );
                }
            }
        }

        foreach($listAlbumID as $node)
        {
            $image = $dm->createQueryBuilder('Application\Document\Image')
                ->select()
                ->field('albumid')->equals($node['id'])
                ->getQuery()
                ->execute();

            $photosArr = array();
            $photosArr['name'] = $node['albumName'];
            $photosArr['lat'] = floatval($node['albumLat']);
            $photosArr['lng'] = floatval($node['albumLong']);

            if(isset($image))
            {
                $i = 0;
                foreach($image as $img)
                {
                    $photosArr['photos'][] =  array(
                        'index'       => $i,
                        'thumb'       => 'uploads/'.$img->getImageid().'.'.$img->getImagetype(),
                        'source'      => 'uploads/'.$img->getImageid().'.'.$img->getImagetype(),
                        'description' => $img->getImagedescription(),
                        'lat'         => floatval($img->getImagelatitude()),
                        'lng'         => floatval($img->getImagelongtitude()),
                    );
                    $i++;
                }
            }
            $placesArr[] = $photosArr;
        }


        $extraArray = array(
            'name'        => $name,
            'description' => $description,
            'places'      => $placesArr,
        );

        return $extraArray;

    }
}