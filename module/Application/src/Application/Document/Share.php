<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/19/13
 * Time: 9:43 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="share") */
class Share
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $shareid;

    /** @ODM\Field(type="string") */
    private $actionid;

    /** @ODM\Field(type="string") */
    private $shareuserid;

    /** @ODM\Field(type="string") */
    private $sharetype;

    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $shareid
     */
    public function getShareid()
    {
        return $this->shareid;
    }

    /**
     * @return the $actionid
     */
    public function getActionid()
    {
        return $this->actionid;
    }

    /**
     * @return the $shareuserid
     */
    public function getShareuserid()
    {
        return $this->shareuserid;
    }

    /**
     * @return the $sharetype
     */
    public function getSharetype()
    {
        return $this->sharetype;
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
     * @param field_type $shareid
     */
    public function setShareid($shareid)
    {
        $this->shareid = $shareid;
    }

    /**
     * @param field_type $actionid
     */
    public function setActionid($actionid)
    {
        $this->actionid = $actionid;
    }

    /**
     * @param field_type $shareuserid
     */
    public function setShareuserid($shareuserid)
    {
        $this->shareuserid = $shareuserid;
    }

    /**
     * @param field_type $sharetype
     */
    public function setSharetype($sharetype)
    {
        $this->sharetype = $sharetype;
    }


}