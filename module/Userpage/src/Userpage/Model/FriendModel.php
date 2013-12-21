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
    public  function getListFriend($actionUser,$dm)
    {
//        $qb = $dm->createQueryBuilder('Application\Document\Friend');
//        $qb->field('friendstatus')->equals("SENT");
//        $qb->addOr()->field('friendusersend')->equals('user1386567908');

        $qb = $dm->createQueryBuilder('Application\Document\Friend')
                 ->field('friendusersend')->equals($actionUser)
                 ->field('friendstatus')->equals('ACCEPTED')
                 ->getQuery()
                 ->execute();

        $qb2 = $dm->createQueryBuilder('Application\Document\Friend')
            ->field('frienduserrecieve')->equals($actionUser)
            ->field('friendstatus')->equals('ACCEPTED')
            ->getQuery()
            ->execute();

        $result = array();

//        var_dump($result); die();

        if(isset($qb))
        {
            foreach($qb as $abc)
            {
                $result[] = $abc->getFrienduserrecieve();
            }

            if(isset($qb2))
            {
                foreach($qb2 as $abc)
                {
                    $result[] = $abc->getFriendusersend();
                }
            }
                    var_dump($result); die();
            return $result;
        }
        else
            return null;

    }
}