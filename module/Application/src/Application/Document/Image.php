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

/** @ODM\Document(collection="image") */
class Image
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $imageid;

    /** @ODM\Field(type="string") */
    private $albumid;

    /** @ODM\Field(type="string") */
    private $imagedescription;

    /** @ODM\Field(type="string") */
    private $imagestatus;

    /** @ODM\Field(type="string") */
    private $imagetype;

    /** @ODM\Field(type="string") */
    private $imagelongtitude;

    /** @ODM\Field(type="string") */
    private $imagelatitude;

    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $imageid
     */
    public function getImageid()
    {
        return $this->imageid;
    }

    /**
     * @return the $albumid
     */
    public function getAlbumid()
    {
        return $this->albumid;
    }

    /**
     * @return the $imagedescription
     */
    public function getImagedescription()
    {
        return $this->imagedescription;
    }

    /**
     * @return the $imagestatus
     */
    public function getImagestatus()
    {
        return $this->imagestatus;
    }

    /**
     * @return the $imagetype
     */
    public function getImagetype()
    {
        return $this->imagetype;
    }

    /**
     * @return the $imagelongtitude
     */
    public function getImagelongtitude()
    {
        return $this->imagelongtitude;
    }

    /**
     * @return the $imagelatitude
     */
    public function getImagelatitude()
    {
        return $this->imagelatitude;
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
     * @param field_type $imageid
     */
    public function setImageid($imageid)
    {
        $this->imageid = $imageid;
    }

    /**
     * @param field_type $albumid
     */
    public function setAlbumid($albumid)
    {
        $this->albumid = $albumid;
    }

    /**
     * @param field_type $imagedescription
     */
    public function setImagedescription($imagedescription)
    {
        $this->imagedescription = $imagedescription;
    }

    /**
     * @param field_type $imagestatus
     */
    public function setImagestatus($imagestatus)
    {
        $this->imagestatus = $imagestatus;
    }

    /**
     * @param field_type $imagetype
     */
    public function setImagetype($imagetype)
    {
        $this->imagetype = $imagetype;
    }

    /**
     * @param field_type $imagelongtitude
     */
    public function setImagelongtitude($imagelongtitude)
    {
        $this->imagelongtitude = $imagelongtitude;
    }

    /**
     * @param field_type $imagelatitude
     */
    public function setImagelatitude($imagelatitude)
    {
        $this->imagelatitude = $imagelatitude;
    }
}