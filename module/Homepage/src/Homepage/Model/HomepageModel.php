<?php

namespace Homepage\Model;

use Application\Document\User;
use Application\Document\Image;

class HomepageModel {

    public function getTimestampNow()
    {
        $date = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        return $date->getTimestamp();
    }

    public function getUserInfo($dm, $userID) {
        $document = $dm->getRepository('Application\Document\User')->findOneBy(array('userid' => $userID));
        if(isset($document))
        {
            return $document;
        }
        else
            return null;
    }

    public function getPathImageAvatarUser($dm, $userid,  $albumType)
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

    public function getUserFriend($dm, $userID) {
        $query = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendstatus')->equals('ACCEPTED');
        $query->addOr($query->expr()->field('friendusersend')->equals($userID));
        $query->addOr($query->expr()->field('frienduserreceive')->equals($userID));

        $result = $query->getQuery()->execute();

        return $result;
    }
}