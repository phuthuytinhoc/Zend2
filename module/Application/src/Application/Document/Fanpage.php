<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/31/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */


namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="fanpage") */
class Fanpage
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $pageid;

    /** @ODM\Field(type="string") */
    private $pagetype;

    /** @ODM\Field(type="string") */
    private $pagename;

    /** @ODM\Field(type="string") */
    private $pagedescription;

    /** @ODM\Field(type="string") */
    private $userid;

    /** @ODM\Field(type="string") */
    private $createdtime;

    /** @ODM\Field(type="string") */
    private $pagelongtitude;

    /** @ODM\Field(type="string") */
    private $pagelatitude;

    /** @ODM\Field(type="string") */
    private $pagemapinfo;

    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $pageid
     */
    public function getPageid()
    {
        return $this->pageid;
    }

    /**
     * @return the $pagetype
     */
    public function getPagetype()
    {
        return $this->pagetype;
    }

    /**
     * @return the $pagename
     */
    public function getPagename()
    {
        return $this->pagename;
    }

    /**
     * @return the $pagedescription
     */
    public function getPagedescription()
    {
        return $this->pagedescription;
    }

    /**
     * @return the $userid
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @return the $createdtime
     */
    public function getCreatedtime()
    {
        return $this->createdtime;
    }

    /**
     * @return the $pagelongtitude
     */
    public function getPagelongtitude()
    {
        return $this->pagelongtitude;
    }

    /**
     * @return the $pagelatitude
     */
    public function getPagelatitude()
    {
        return $this->pagelatitude;
    }

    /**
     * @return the $pagemapinfo
     */
    public function getPagemapinfo()
    {
        return $this->pagemapinfo;
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
     * @param field_type $pageid
     */
    public function setPageid($pageid)
    {
        $this->pageid = $pageid;
    }

    /**
     * @param field_type $pagetype
     */
    public function setPagetype($pagetype)
    {
        $this->pagetype = $pagetype;
    }

    /**
     * @param field_type $pagename
     */
    public function setPagename($pagename)
    {
        $this->pagename = $pagename;
    }

    /**
     * @param field_type $pagedescription
     */
    public function setPagedescription($pagedescription)
    {
        $this->pagedescription = $pagedescription;
    }

    /**
     * @param field_type $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @param field_type $createdtime
     */
    public function setCreatedtime($createdtime)
    {
        $this->createdtime = $createdtime;
    }

    /**
     * @param field_type $pagelongtitude
     */
    public function setPagelongtitude($pagelongtitude)
    {
        $this->pagelongtitude = $pagelongtitude;
    }

    /**
     * @param field_type $pagelatitude
     */
    public function setPagelatitude($pagelatitude)
    {
        $this->pagelatitude = $pagelatitude;
    }

    /**
     * @param field_type $pagemapinfo
     */
    public function setPagemapinfo($pagemapinfo)
    {
        $this->pagemapinfo = $pagemapinfo;
    }
}