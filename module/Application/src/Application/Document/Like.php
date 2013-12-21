<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/19/13
 * Time: 9:40 AM
 * To change this template use File | Settings | File Templates.
 */


namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="like") */
class Like
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $likeid;

    /** @ODM\Field(type="string") */
    private $actionid;




    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $likeid
     */
    public function getLikeid()
    {
        return $this->likeid;
    }

    /**
     * @return the $actionid
     */
    public function getActionid()
    {
        return $this->actionid;
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
     * @param field_type $likeid
     */
    public function setLikeid($likeid)
    {
        $this->likeid = $likeid;
    }

    /**
     * @param field_type $actionid
     */
    public function setActionid($actionid)
    {
        $this->actionid = $actionid;
    }


}