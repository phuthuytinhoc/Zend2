<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 1/2/14
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="fanpagemanage") */
class FanpageManage
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $pageid;

    /** @ODM\Field(type="string") */
    private $userid;

    /** @ODM\Field(type="string") */
    private $pageuserstatus;


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
     * @return the $userid
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @return the $pageuserstatus
     */
    public function getPageuserstatus()
    {
        return $this->pageuserstatus;
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
     * @param field_type $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @param field_type $pageuserstatus
     */
    public function setPageuserstatus($pageuserstatus)
    {
        $this->pageuserstatus = $pageuserstatus;
    }
}