<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/15/13
 * Time: 8:01 AM
 * To change this template use File | Settings | File Templates.
 */


namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="comment") */
class Comment
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $commentid;

    /** @ODM\Field(type="string") */
    private $actionid;

    /** @ODM\Field(type="string") */
    private $commentcontent;


    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $commentid
     */
    public function getCommentid()
    {
        return $this->commentid;
    }

    /**
     * @return the $actionid
     */
    public function getActionid()
    {
        return $this->actionid;
    }

    /**
     * @return the $commentcontent
     */
    public function getCommentcontent()
    {
        return $this->commentcontent;
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
     * @param field_type $commentid
     */
    public function setCommentid($commentid)
    {
        $this->commentid = $commentid;
    }

    /**
     * @param field_type $actionid
     */
    public function setActionid($actionid)
    {
        $this->actionid = $actionid;
    }

    /**
     * @param field_type $commentcontent
     */
    public function setCommentcontent($commentcontent)
    {
        $this->commentcontent = $commentcontent;
    }

}