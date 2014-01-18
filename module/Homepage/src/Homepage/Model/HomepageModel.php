<?php

namespace Homepage\Model;

use Application\Document\User;
use Application\Document\Image;
use Zend\Validator\Regex;

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
        $userList = array();

        // get list user friend with user_send = current user
        $query = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendstatus')->equals('ACCEPTED')
            ->field('friendusersend')->equals($userID)
            ->getQuery();

        $results = $query->execute();

        foreach ($results as $result) {
            $userList[] = $result->getFrienduserrecieve();
        }

        // get list user friend with user_receive = current user
        $query = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendstatus')->equals('ACCEPTED')
            ->field('frienduserrecieve')->equals($userID)
            ->getQuery();

        $results = $query->execute();

        foreach ($results as $result) {
            $userList[] = $result->getFriendusersend();
        }

        // After get all friends of user, we add current user into list
        $userList[] = $userID;

        return $userList;
    }

    public function getAllAction($dm, $userList = array(), $currentuserid) {
        $actionList = array();
        $actionContentList = array();

        foreach ($userList as $user) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionuser')->equals($user);
            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('STT.*/i')));
            $results = $query->sort('createdtime', 'desc')->getQuery()->execute();

            foreach ($results as $result) {
                $actionList[] = $result->getActionid();
             }
        }

        foreach ($userList as $user) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionuser')->equals($user);
            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('PLA.*/i')));
//            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('IMG.*/i')));
//            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('VID.*/i')));

            $results = $query->sort('createdtime', 'desc')->getQuery()->execute();

            foreach ($results as $result) {
                $actionList[] = $result->getActionid();
            }
        }

        foreach ($userList as $user) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionuser')->equals($user);
            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('IMG.*/i')));
            $results = $query->sort('createdtime', 'desc')->getQuery()->execute();

            foreach ($results as $result) {
                $actionList[] = $result->getActionid();
            }
        }

        foreach ($userList as $user) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actionuser')->equals($user);
            $query->addOr($query->expr()->field('actiontype')->equals(new \MongoRegex('VID.*/i')));
            $results = $query->sort('createdtime', 'desc')->getQuery()->execute();

            foreach ($results as $result) {
                $actionList[] = $result->getActionid();
            }
        }

        arsort($actionList);
        $i = 0;
        foreach ($actionList as $action) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->select('actionid', 'actionuser', 'actionlocation', 'actiontype', 'createdtime')
                ->field('actionid')->equals($action);

            $results = $query->getQuery()->execute();
            foreach ($results as $result) {
                $actionContentList[$i]['type'] = '';
                if (strpos($result->getActionType(), 'STT') !== false) {
                    $actionContentList[$i]['type'] = 'STT';
                }elseif (strpos($result->getActionType(), 'IMG') !== false) {
                    $actionContentList[$i]['type'] = 'IMG';
                    $actionContentList[$i]['mediapath'] = $this->getMediaPath($dm, $result->getActionType(), $actionContentList[$i]['type']);
                }elseif (strpos($result->getActionType(), 'VID') !== false) {
                    $actionContentList[$i]['type'] = 'VID';
                    $actionContentList[$i]['mediapath'] = $this->getMediaPath($dm, $result->getActionType(), $actionContentList[$i]['type']);
                }elseif (strpos($result->getActionType(), 'PLA') !== false) {
                    $actionContentList[$i]['type'] = 'PLA';
                    $actionContentList[$i]['mediapath'] = $this->getMediaPath($dm, $result->getActionType(), $actionContentList[$i]['type']);
                    $actionContentList[$i]['placename'] = $this->getPlaceName($dm, $result->getActionType());
                    $actionContentList[$i]['placehashtag'] = $this->getPlaceHashtag($dm, $result->getActionType());
                }
                $actionContentList[$i]['actionid'] = $result->getActionid();
                $actionContentList[$i]['actionuser'] = $result->getActionUser();
                $actionContentList[$i]['actionlocation'] = $result->getActionLocation();
                $actionContentList[$i]['createdtime'] = $result->getCreatedTime();
                $actionContentList[$i]['actiontype'] = $result->getActionType();
                $actionContentList[$i]['content'] = $this->getActionContent($dm, $result->getActionType(), $actionContentList[$i]['type']);
                $actionContentList[$i]['username'] = $this->getUserName($dm, $result->getActionUser());
                $actionContentList[$i]['avatarpath'] = $this->getUserAvatarPath($dm, $result->getActionUser());
                $actionContentList[$i]['actionlocationname'] = $this->getUserName($dm, $result->getActionLocation());
                $check = $this->checklike($dm, $result->getActionid(), $currentuserid);
                if($check == true) {
                    $actionContentList[$i]['checklike'] = 1;
                }else {
                    $actionContentList[$i]['checklike'] = 0;
                }
                $actionContentList[$i]['likenumber'] = $this->countlike($dm, $result->getActionid());
                $actionContentList[$i]['commentnumber'] = $this->countcomment($dm, $result->getActionid());

                /**
                 * THIS PART USE TO LOAD COMMENT OF ACTION
                 */

                $query = $dm->createQueryBuilder('Application\Document\Comment')
                    ->field('actionid')->equals($result->getActionid());

                $commentids = $query->getQuery()->execute();
                if ($commentids) {
                    $j = 0;
                    foreach ($commentids as $commentid) {
                        $actionContentList[$i]['comment'][$j]['content'] = $commentid->getCommentcontent();
                        $query = $dm->createQueryBuilder('Application\Document\Action')
                            ->field('actiontype')->equals($commentid->getCommentid());

                        $actionids = $query->getQuery()->execute();
                        foreach ($actionids as $actionid) {
                            $actionContentList[$i]['comment'][$j]['actionuser'] = $actionid->getActionUser();
                            $actionContentList[$i]['comment'][$j]['createdtime'] = $actionid->getCreatedTime();
                            $actionContentList[$i]['comment'][$j]['username'] = $this->getUserName($dm, $actionid->getActionUser());
                            $actionContentList[$i]['comment'][$j]['avatarpath'] = $this->getUserAvatarPath($dm, $actionid->getActionUser());
                            $actionContentList[$i]['comment'][$j]['likenumber'] = $this->countlike($dm, $actionid->getActionid());
                            $actionContentList[$i]['comment'][$j]['actionid'] = $actionid->getActionid();

                            $check = $this->checklike($dm, $actionid->getActionid(), $currentuserid);
                            if($check == true) {
                                $actionContentList[$i]['comment'][$j]['checklike'] = 1;
                            }else {
                                $actionContentList[$i]['comment'][$j]['checklike'] = 0;
                            }
                        }
                        $j++;
                    }
                }else {
                    $actionContentList[$i]['comment'] = NULL;
                }

            }
            $i++;
        }

//        return $actionList;
        return $actionContentList;
    }

    public function getActionContent ($dm, $actiontype, $type) {

        if ($type == 'STT') {
            $query = $dm->createQueryBuilder('Application\Document\Status')
                ->field('statusid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                return $result->getStatusContent();
            }
        }elseif ($type == 'IMG') {
            $query = $dm->createQueryBuilder('Application\Document\Image')
                ->field('imageid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                return $result->getImagedescription();
            }
        }elseif ($type == 'VID') {
            $query = $dm->createQueryBuilder('Application\Document\Video')
                ->field('videoid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                return $result->getVideodescription();
            }
        }elseif ($type == 'PLA') {
            $query = $dm->createQueryBuilder('Application\Document\Place')
                ->field('placeid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                return $result->getPlacedescription();
            }
        }
    }

    public function getMediaPath($dm, $actiontype, $type) {
        if ($type == 'IMG') {
            $query = $dm->createQueryBuilder('Application\Document\Image')
                ->field('imageid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                $path = $result->getImageid().'.'.$result->getImagetype();

                return $path;
            }
        }elseif ($type == 'VID') {
            $query = $dm->createQueryBuilder('Application\Document\Video')
                ->field('videoid')->equals($actiontype);

            $results = $query->getQuery()->execute();

            foreach ($results as $result) {
                $path = $result->getVideoid().'.'.$result->getVideotype();

                return $path;
            }
        }elseif ($type == 'PLA') {
            $query = $dm->createQueryBuilder('Application\Document\Place')
                ->field('placeid')->equals($actiontype);

            $results = $query->getQuery()->getSingleResult();

            $image = $results->getImageid();
            $query = $dm->createQueryBuilder('Application\Document\Image')
                ->field('imageid')->equals($image);

            $result = $query->getQuery()->getSingleResult();

            $imagetype = $result->getImagetype();

            $path = $image.'.'.$imagetype;

            return $path;

            return $image;
        }
    }

    public function getPlaceName($dm, $actiontype) {
        $query = $dm->createQueryBuilder('Application\Document\Place')
            ->field('placeid')->equals($actiontype);

        $result = $query->getQuery()->getSingleResult();

        return $result->getPlacename();
    }

    public function getPlaceHashtag($dm, $actiontype) {
        $query = $dm->createQueryBuilder('Application\Document\Place')
            ->field('placeid')->equals($actiontype);

        $result = $query->getQuery()->getSingleResult();

        return $result->getHashtag();
    }

    public function getUserName($dm, $userid) {
        $query = $dm->createQueryBuilder('Application\Document\User')
            ->field('userid')->equals($userid);

        $result = $query->getQuery()->getSingleResult();

        return $result->getLastname().' '.$result->getFirstname();
    }

    public function getUserAvatarPath($dm, $userid) {
        $query = $dm->createQueryBuilder('Application\Document\Image')
            ->field('albumid')->equals('ALB'.$userid.'AVA')
            ->field('imagestatus')->equals('AVA_NOW');

        $result = $query->getQuery()->getSingleResult();

        if(isset($result)) {
            return $result->getImageid().'.'.$result->getImagetype();
        }else {
            return 'ava-temp.png';
        }
    }

    public function saveLike($dm, $data) {
        $timecreated = $data['actiontime'];
        $actionid = $data['actionid'];
        $actionuser = $data['actionuser'];

        /**
         * STEP 1: WE SELECT ALL LIKE ACTION OF CURRENT ACTION
         */
        $query = $dm->createQueryBuilder('Application\Document\Like')
            ->field('actionid')->equals($actionid);

        $results = $query->getQuery()->execute();

        /**
         * STEP 2: SELECT ALL USER LIKE CURRENT ACTION
         */
        foreach ($results as $result) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actiontype')->equals($result->getLikeid());

            $results1 = $query->getQuery()->execute();
            foreach ($results1 as $result1) {
                /**
                 * IF WE HAVE ACTIONUSER == CURRENT USER
                 * MEAN CURRENT USER HAVE LIKE CURRENT ACTION
                 */
                if ($result1->getActionUser() == $actionuser) {
                    $dm->createQueryBuilder('Application\Document\Action')
                        ->remove()
                        ->field('actiontype')->equals($result1->getActionType())
                        ->field('actionuser')->equals($result1->getActionUser())
                        ->getQuery()
                        ->execute();

                    $dm->createQueryBuilder('Application\Document\Like')
                        ->remove()
                        ->field('likeid')->equals($result->getLikeid())
                        ->getQuery()
                        ->execute();
                    if ($dm)
                        return 'dislike';
                    else
                        return false;
                }
            }
        }

        $dm->createQueryBuilder('Application\Document\Like')
            ->insert()
            ->field('likeid')->set('LIK'.$actionuser.$timecreated)
            ->field('actionid')->set($actionid)
            ->getQuery()
            ->execute();

        $dm->createQueryBuilder('Application\Document\Action')
            ->insert()
            ->field('actionid')->set('ACT'.$timecreated)
            ->field('actionuser')->set($actionuser)
            ->field('actionlocation')->set($actionuser)
            ->field('actiontype')->set('LIK'.$actionuser.$timecreated)
            ->field('createdtime')->set($timecreated)
            ->getQuery()
            ->execute();

            if(isset($dm))
                return 'like';
            else
                return false;
    }

    public function checklike($dm, $actionid, $currentuserid) {
        $query = $dm->createQueryBuilder('Application\Document\Like')
            ->field('actionid')->equals($actionid);

        $results = $query->getQuery()->execute();

        foreach ($results as $result) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->field('actiontype')->equals($result->getLikeid());

            $result1 = $query->getQuery()->getSingleResult();
            if ($result1->getActionUser() == $currentuserid) {
                return true;
            }
        }

        return false;
    }

    public function countlike($dm, $actionid) {
        $query = $dm->createQueryBuilder('Application\Document\Like')
            ->field('actionid')->equals($actionid);

        $count = $query->getQuery()->execute()->count();

        return $count;
    }

    public function countcomment($dm, $actionid) {
        $query = $dm->createQueryBuilder('Application\Document\Comment')
            ->field('actionid')->equals($actionid);

        $count = $query->getQuery()->execute()->count();

        return $count;
    }

    public function saveComment($dm, $data) {
        $timecreated = $data['actiontime'];
        $actionuser = $data['actionuser'];
        $actionid = $data['actionid'];
        $commentcontent = $data['commentcontent'];

        $query = $dm->createQueryBuilder('Application\Document\Comment')
            ->insert()
            ->field('commentid')->set('CMT'.$actionuser.$timecreated)
            ->field('actionid')->set($actionid)
            ->field('commentcontent')->set($commentcontent);

        $result = $query->getQuery()->execute();
        if (isset($result)) {
            $query = $dm->createQueryBuilder('Application\Document\Action')
                ->insert()
                ->field('actionid')->set('ACT'.$timecreated)
                ->field('actionuser')->set($actionuser)
                ->field('actionlocation')->set($actionuser)
                ->field('actiontype')->set('CMT'.$actionuser.$timecreated)
                ->field('createdtime')->set($timecreated);

            $result2 = $query->getQuery()->execute();

            if (isset($result2)) {
                return 'ACT'.$timecreated;
            }
        }

        return false;
    }

    public function getOnlyUserFriend($dm, $userID) {
        $userList = array();

        // get list user friend with user_send = current user
        $query = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendstatus')->equals('ACCEPTED')
            ->field('friendusersend')->equals($userID)
            ->getQuery();

        $results = $query->execute();

        foreach ($results as $result) {
            $userList[] = $result->getFrienduserrecieve();
        }

        // get list user friend with user_receive = current user
        $query = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendstatus')->equals('ACCEPTED')
            ->field('frienduserrecieve')->equals($userID)
            ->getQuery();

        $results = $query->execute();

        foreach ($results as $result) {
            $userList[] = $result->getFriendusersend();
        }

        return $userList;
    }

    public function countMuturalFriend($dm, $user1, $user2) {
        $user1List = $this->getOnlyUserFriend($dm, $user1);

        $user2List = $this->getOnlyUserFriend($dm, $user2);

        $count = 0;
        foreach ($user1List as $user1Friend) {
            foreach ($user2List as $user2Friend) {
                if ($user2Friend == $user1Friend) {
                    $count += 1;
                }
            }
        }

        return $count;
    }

    public function searchFriend($dm, $string, $currentuser) {
        if ($dm) {
            $query = $dm->createQueryBuilder('Application\Document\User');
//                ->field('firstname')->equals(new \MongoRegex('/.*'.$string.'.*/i'));
            $query->addOr($query->expr()->field('lastname')->equals(new \MongoRegex('/.*'.$string.'.*/i')));
            $query->addOr($query->expr()->field('firstname')->equals(new \MongoRegex('/.*'.$string.'.*/i')));

            $results = $query->getQuery()->execute();

            $i = 0;
            $searchList = array();
            if ($results) {
                foreach ($results as $result) {
                    if ($result->getUserid() != $currentuser) {
                        $searchList[$i]['muturalfriend'] = $this->countMuturalFriend($dm, $currentuser, $result->getUserid()). ' bạn chung';
                    }else {
                        $searchList[$i]['muturalfriend'] = '';
                    }
                    $searchList[$i]['username'] = $this->getUserName($dm, $result->getUserid());
                    $searchList[$i]['userid'] = $result->getUserid();
                    $searchList[$i]['avatarpath'] = $this->getUserAvatarPath($dm, $result->getUserid());
                    if ($result->getAddress()) {
                        $searchList[$i]['additioninfo'] = $result->getAddress();
                    }elseif ($result->getWork()) {
                            $searchList[$i]['additioninfo'] = $result->getWork();
                        }elseif ($result->getSchool()) {
                                $searchList[$i]['additioninfo'] = $result->getSchool();
                            }elseif ($result->getRelationship()) {
                                    $searchList[$i]['additioninfo'] = $result->getRelationship();
                                }else {
                                    $searchList[$i]['additioninfo'] = '';
                    }

                    $i += 1;
                }

                return $searchList;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function searchPlace($dm, $string) {

        if ($dm) {
            $query = $dm->createQueryBuilder('Application\Document\Place')
                ->field('hashtag')->equals(new \MongoRegex('/.*'.$string.'.*/i'));

            $results = $query->getQuery()->execute();
            $i = 0;
            $searchList = array();

            if($results) {
                foreach ($results as $result) {
                    $count = $dm->createQueryBuilder('Application\Document\Place')
                        ->field('hashtag')->equals($result->getHashtag())
                        ->getQuery()
                        ->execute()
                        ->count();
                    $searchList[$i]['placename'] = $result->getPlacename();
                    $searchList[$i]['placeid'] = $result->getPlaceid();
                    $searchList[$i]['count'] = $count;
                    $searchList[$i]['hashtag'] = $result->getHashtag();

                    $imagetype = $this->getImageType($dm, $result->getImageid());

                    $searchList[$i]['imagepath'] = $result->getImageid().'.'.$imagetype;

                    $i += 1;
                }

                return $searchList;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function getImageType($dm, $imageID) {
        $query = $dm->createQueryBuilder('Application\Document\Image')
            ->field('imageid')->equals($imageID);

        $result = $query->getQuery()->getSingleResult();

        return $result->getImagetype();
    }
}