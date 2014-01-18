<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/2/14
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Model;

use Application\Document;

class CreatePageModel
{
    public function getTimestampNow()
    {
        $date = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $date->getTimestamp();
    }

    public function getListPageofUser($data, $dm)
    {
        $userID = $data;
        $arr = null;

        $doc = $dm->createQueryBuilder('Application\Document\Fanpage')
            ->select()
            ->field('userid')->equals($userID)
            ->getQuery()
            ->execute();
        if(isset($doc))
        {
            foreach($doc as $node)
            {
                $pageID = $node->getPageid();
                $checkAlbumID = 'ALB'.$pageID.'AVA';
                $imageName = 'ava-page-temp.png';
                $image = $dm->createQueryBuilder('Application\Document\Image')
                    ->select()
                    ->field('albumid')->equals($checkAlbumID)
                    ->field('imagestatus')->equals('AVA_NOW')
                    ->getQuery()
                    ->getSingleResult();

                if(isset($image))
                {
                    $imageName = $image->getImageid().'.'.$image->getImagetype();
                }

                $arr[] = array(
                    'pageName' => $node->getPagename(),
                    'pageID'   => $pageID,
                    'pageType' => $node->getPagetype(),
                    'pageTime' => $node->getCreatedtime(),
                    'pageAva'  => $imageName,
                );
            }
        }
        return $arr;
    }

    public function createNewFanpage($data, $userid, $dm)
    {
        //FANPAGE
        $createdTime = $this->getTimestampNow();
        $pageID = 'page'.$createdTime;
        $pageName =  $data['pageName'];
        $pageType = $data['pageType'];
        $pageDescription =  $data['pageDescription'];

        //FANPAGE MANAGE
        $pageuserstatus = "ADMIN";

        $document = $dm->createQueryBuilder('Application\Document\Fanpage')
            ->insert()
            ->field('pageid')->set($pageID)
            ->field('pagetype')->set($pageType)
            ->field('pagename')->set($pageName)
            ->field('pagedescription')->set($pageDescription)
            ->field('userid')->set($userid)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        $doc = $dm->createQueryBuilder('Application\Document\FanpageManage')
            ->insert()
            ->field('pageid')->set($pageID)
            ->field('userid')->set($userid)
            ->field('pageuserstatus')->set($pageuserstatus)
            ->getQuery()
            ->execute();

        if(isset($document) && isset($doc))
        {
            return $pageID;
        }
        else
        {
            return null;
        }
    }


}