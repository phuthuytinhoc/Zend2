<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/11/13
 * Time: 10:20 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="action") */
class Action
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $actionid;

    /** @ODM\Field(type="string") */
    private $actionuser;

    /** @ODM\Field(type="string") */
    private $actionlocation;

    /** @ODM\Field(type="string") */
    private $actiontype;

    /** @ODM\Field(type="string") */
    private $actionLocate;

    /** @ODM\Field(type="string") */
    private $actionlongtitude;

    /** @ODM\Field(type="string") */
    private $actionlatitude;

    /** @ODM\Field(type="string") */
    private $createdtime;

    /** @ODM\Field(type="string") */
    private $updatetime;

    /** @ODM\Field(type="string") */
    private $allowID;

    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $actionid
     */
    public function getActionid()
    {
        return $this->actionid;
    }

    /**
     * @return the $actionuser
     */
    public function getActionUser()
    {
        return $this->actionuser;
    }

    /**
     * @return the $actionlocation
     */
    public function getActionLocation()
    {
        return $this->actionlocation;
    }

    /**
     * @return the $actiontype
     */
    public function getActionType()
    {
        return $this->actiontype;
    }

    /**
     * @return the $actionLocate
     */
    public function getActionLocate()
    {
        return $this->actionLocate;
    }

    /**
     * @return the $actionlongtitude
     */
    public function getActionLongtitude()
    {
        return $this->actionlongtitude;
    }

    /**
     * @return the $actionlatitude
     */
    public function getActionLatitude()
    {
        return $this->actionlatitude;
    }

    /**
     * @return the $createdtime
     */
    public function getCreatedTime()
    {
        return $this->createdtime;
    }

    /**
     * @return the $updatetime
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    /**
     * @return the $allowID
     */
    public function getAllowID()
    {
        return $this->allowID;
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
     * @param field_type $actionid
     */
    public function setActionid($actionid)
    {
        $this->actionid = $actionid;
    }

    /**
     * @param field_type $actionuser
     */
    public function setActionUser($actionuser)
    {
        $this->actionuser = $actionuser;
    }

    /**
     * @param field_type $actionlocation
     */
    public function setActionLocation($actionlocation)
    {
        $this->actionlocation = $actionlocation;
    }

    /**
     * @param field_type $actiontype
     */
    public function setActionType($actiontype)
    {
        $this->actiontype = $actiontype;
    }

    /**
     * @param field_type $actionLocate
     */
    public function setActionLocate($actionLocate)
    {
        $this->actionLocate = $actionLocate;
    }

    /**
     * @param field_type $actionlongtitude
     */
    public function setActionLongtitude($actionlongtitude)
    {
        $this->actionlongtitude = $actionlongtitude;
    }

    /**
     * @param field_type $actionid
     */
    public function setActionLatitude($actionlatitude)
    {
        $this->actionlatitude = $actionlatitude;
    }

    /**
     * @param field_type $createdtime
     */
    public function setCreatedtime($createdtime)
    {
        $this->createdtime = $createdtime;
    }

    /**
     * @param field_type $updatetime
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;
    }

    /**
     * @param field_type $allowID
     */
    public function setAllowID($allowID)
    {
        $this->allowID = $allowID;
    }
}