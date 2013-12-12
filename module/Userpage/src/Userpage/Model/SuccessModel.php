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

    //FUNCTION FOR TRANG CA NHAN
    public function saveNewStatus($statusContent, $userid, $documentService )
    {
        $createdTime = $this->getTimestampNow();

        //collection('status')
        $statusID = 'STT'. $createdTime;

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
            ->field('actionid')->set($statusID)
            ->field('actionuser')->set($actionUser)
            ->field('actionlocation')->set($actionLocation)
            ->field('actiontype')->set($actionType)
            ->field('createdtime')->set($createdTime)
            ->getQuery()
            ->execute();

        return true;
    }
}