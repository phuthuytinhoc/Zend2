<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/13/13
 * Time: 11:49 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="album") */
class Album
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $albumid;

    /** @ODM\Field(type="string") */
    private $albumdescription;

    /** @ODM\Field(type="string") */
    private $albumname;

    /** @ODM\Field(type="string") */
    private $userid;


    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $albumid
     */
    public function getAlbumid()
    {
        return $this->albumid;
    }

    /**
     * @return the $albumdescription
     */
    public function getAlbumdescription()
    {
        return $this->albumdescription;
    }

    /**
     * @return the $albumname
     */
    public function getAlbumname()
    {
        return $this->albumname;
    }

    /**
     * @return the $userid
     */
    public function getUserid()
    {
        return $this->userid;
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
     * @param field_type $albumid
     */
    public function setAlbumid($albumid)
    {
        $this->albumid = $albumid;
    }

    /**
     * @param field_type $albumdescription
     */
    public function setAlbumdescription($albumdescription)
    {
        $this->albumdescription = $albumdescription;
    }

    /**
     * @param field_type $albumname
     */
    public function setAlbumname($albumname)
    {
        $this->albumname = $albumname;
    }

    /**
     * @param field_type $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }
}