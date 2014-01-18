<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/21/13
 * Time: 12:44 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage\Model;

use Application\Document;

class FriendModel
{
    public function getListFriend($actionUser,$dm)
    {
        $numberFriend = 0;
        $numberRequest = 0;

        $qb = $dm->createQueryBuilder('Application\Document\Friend')
                 ->field('friendusersend')->equals($actionUser)
                 ->getQuery()
                 ->execute();

        $qb2 = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('frienduserrecieve')->equals($actionUser)
            ->getQuery()
            ->execute();

        $result = array();
        $checkStatusFriend = array();

        if(isset($qb))
        {
            foreach($qb as $abc)
            {
                $userid = $abc->getFrienduserrecieve();
                $result[] = $userid;
                $fstt = $abc->getFriendstatus();
                $checkStatusFriend[$userid] = $fstt;
                if($fstt == "ACCEPTED")
                    $numberFriend++;
                else
                    $numberRequest++;
            }

        }

        if(isset($qb2))
        {
            foreach($qb2 as $abc)
            {
                $userid =$abc->getFriendusersend();
                $result[] = $userid;
                $fstt = $abc->getFriendstatus();
                $checkStatusFriend[$userid] = $fstt;
                if($fstt == "ACCEPTED")
                    $numberFriend++;
                else
                    $numberRequest++;
            }
        }


        $infoFriends = array();
        $arrayB = array();
        $banchung = array();

        if($result != null && isset($result)){
            foreach($result as $userid)
            {
                $document = $dm->createQueryBuilder('Application\Document\User')
                    ->field('userid')->equals($userid)
                    ->getQuery()
                    ->getSingleResult();
                $document2 = $dm->createQueryBuilder('Application\Document\Image')
                    ->field('albumid')->equals('ALB'.$userid.'AVA')
                    ->field('imagestatus')->equals('AVA_NOW')
                    ->getQuery()
                    ->getSingleResult();
                if(isset($document))
                {
                    if(isset($document2))
                    {
                        $infoFriends[$userid]  = array(
                            'fullname' => $document->getLastname().' '.$document->getFirstname(),
                            'relationship' => $document->getRelationship(),
                            'pathAvatar' => $document2->getImageid().'.'.$document2->getImagetype(),
                        );
                    }
                    else
                    {
                        $infoFriends[$userid]  = array(
                            'fullname' => $document->getLastname().' '.$document->getFirstname(),
                            'relationship' => $document->getRelationship(),
                            'pathAvatar' => 'ava-temp.png',
                        );
                    }
                }

                //tim danh sach ban chung cua user va ban cua user
                $document = $dm->createQueryBuilder('Application\Document\Friend')
                    ->field('friendusersend')->equals($userid)
                    ->field('friendstatus')->equals('ACCEPTED')
                    ->getQuery()
                    ->execute();
                $document2 = $dm->createQueryBuilder('Application\Document\Friend')
                    ->field('frienduserrecieve')->equals($userid)
                    ->field('friendstatus')->equals('ACCEPTED')
                    ->getQuery()
                    ->execute();

                if(isset($document))
                {
                    foreach($document as $doc1)
                    {
                        $arrayB[$userid][] = $doc1->getFrienduserrecieve();
                    }

                }
                if(isset($document2))
                {
                    foreach($document2 as $doc2)
                    {
                        $arrayB[$userid][] = $doc2->getFriendusersend();
                    }
                }

                $count = 0;


                if(isset($arrayB) && $arrayB != null && $result != null){
                    for ($i = 0; $i < count($result); $i++)
                    {
                        if(isset($arrayB[$userid]))
                        {
                            for ($j = 0; $j < count($arrayB[$userid]); $j++)
                            {
                                    if ($result[$i] == $arrayB[$userid][$j])
                                    {
                                        $count = $count + 1;
                                        $banchung[$userid][] = $arrayB[$userid][$j];
                                    }
                            }
                        }
                    }
                }


            }
        }

        return array(
            'countFriend'       => $numberFriend,
            'countRequest'      => $numberRequest,
            'arrayFriendID'     => $result,
            'infoFriends'       => $infoFriends,
            'banchung'          => $banchung,
            'checkStatusFriend' => $checkStatusFriend,

        );


    }


    public function confirmaddfriend($data, $dm)
    {
        $friendUserSend = $data['actionUser'];
        $friendUserRecieve = $data['actionLocation'];
        $friendStatus = $data['friendStatus'];

        $document=$dm->createQueryBuilder('Application\Document\Friend')
            ->update()
            ->field('friendusersend')->equals($friendUserRecieve)
            ->field('frienduserrecieve')->equals($friendUserSend)
            ->field('friendstatus')->set($friendStatus)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;
    }



    public function convertStringVN ($str){

        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

        return $str;

    }

    //SEARCH USER

    public function getImageAvaFromResult($userid, $imageStatus, $dm)
    {
        $document = $dm->createQueryBuilder('Application\Document\Image')
            ->field('albumid')->equals('ALB'.$userid.'AVA')
            ->field('imagestatus')->equals($imageStatus)
            ->getQuery()
            ->getSingleResult();
        $path = "ava-temp.png";
        if(isset($document))
        {
            $path = $document->getImageid().'.'.$document->getImagetype();
        }
        return $path;
    }

    public function checkIsUserFriend($meID, $checkID, $dm)
    {
        $document = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendusersend')->equals($meID)
            ->field('frienduserrecieve')->equals($checkID)
            ->getQuery()
            ->getSingleResult();

        $document2 = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('friendusersend')->equals($checkID)
            ->field('frienduserrecieve')->equals($meID)
            ->getQuery()
            ->getSingleResult();
        $result = "";
        if(isset($document)){

            $result = $document->getFriendstatus();
        }
        if(isset($document2)){
            $result = $document2->getFriendstatus();
        }

        return $result;

    }

    public function getResultSearch($data ,$dm)
    {
        $actionUser = $data['actionUser'];
        $keywords = $this->convertStringVN($data['searchContent']);

        $arr = $dm->createQueryBuilder('Application\Document\User')
            ->getQuery()
            ->execute();

        $arrFullname = array();

        if(isset($arr))
        {
            foreach($arr as $user)
            {
                $arrFullname[$user->getUserid()] = $user->getLastname().' '.$user->getFirstname();
            }
        }

        $matches = array();
        foreach($arrFullname as $k=>$v) {
            $fullName = $v;
            $v = $this->convertStringVN($v);
            if(preg_match("/$keywords/i", $v)) {
                $matches[] = array(
                    'fullName' => $fullName,
                    'path'     => $this->getImageAvaFromResult($k, 'AVA_NOW', $dm),
                    'userid'   => $k,
                    'friendStatus' => $this->checkIsUserFriend($actionUser, $k, $dm),
                );
            }
        }

        return $matches;

    }
}