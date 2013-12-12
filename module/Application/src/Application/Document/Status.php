<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/12/13
 * Time: 10:41 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="status") */
class Status
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $statusid;

    /** @ODM\Field(type="string") */
    private $statuscontent;


    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $statusid
     */
    public function getStatusid()
    {
        return $this->statusid;
    }

    /**
     * @return the $statuscontent
     */
    public function getStatusContent()
    {
        return $this->statuscontent;
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
     * @param field_type $statusid
     */
    public function setStatusid($statusid)
    {
        $this->statusid = $statusid;
    }

    /**
     * @param field_type $statuscontent
     */
    public function setStatusContent($statuscontent)
    {
        $this->statuscontent = $statuscontent;
    }
}