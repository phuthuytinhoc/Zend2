<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/20/13
 * Time: 1:49 PM
 * To change this template use File | Settings | File Templates.
 */


namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="friend") */
class Friend
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $friendusersend;

    /** @ODM\Field(type="string") */
    private $frienduserrecieve;

    /** @ODM\Field(type="string") */
    private $friendstatus;


    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $friendusersend
     */
    public function getFriendusersend()
    {
        return $this->friendusersend;
    }

    /**
     * @return the $frienduserrecieve
     */
    public function getFrienduserrecieve()
    {
        return $this->frienduserrecieve;
    }

    /**
     * @return the $friendstatus
     */
    public function getFriendstatus()
    {
        return $this->friendstatus;
    }


    //*****SET METHOD*****//

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param field_type $friendusersend
     */
    public function setFriendusersend($friendusersend)
    {
        $this->friendusersend = $friendusersend;
    }

    /**
     * @param field_type $frienduserrecieve
     */
    public function setFrienduserrecieve($frienduserrecieve)
    {
        $this->frienduserrecieve = $frienduserrecieve;
    }

    /**
     * @param field_type $friendstatus
     */
    public function setFriendstatus($friendstatus)
    {
        $this->friendstatus = $friendstatus;
    }



}