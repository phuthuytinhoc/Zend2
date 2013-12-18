<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/18/13
 * Time: 11:17 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="place") */
class Place
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $imageid;

    /** @ODM\Field(type="string") */
    private $placeid;

    /** @ODM\Field(type="string") */
    private $placename;

    /** @ODM\Field(type="string") */
    private $placedescription;

    /** @ODM\Field(type="string") */
    private $placeaddress;

    /** @ODM\Field(type="string") */
    private $hashtag;



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
     * @return the $placeid
     */
    public function getPlaceid()
    {
        return $this->placeid;
    }

    /**
     * @return the $placename
     */
    public function getPlacename()
    {
        return $this->placename;
    }

    /**
     * @return the $placedescription
     */
    public function getPlacedescription()
    {
        return $this->placedescription;
    }

    /**
     * @return the $placeaddress
     */
    public function getPlaceaddress()
    {
        return $this->placeaddress;
    }

    /**
     * @return the $hashtag
     */
    public function getHashtag()
    {
        return $this->hashtag;
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
     * @param field_type $placeid
     */
    public function setPlaceid($placeid)
    {
        $this->placeid = $placeid;
    }

    /**
     * @param field_type $placename
     */
    public function setPlacename($placename)
    {
        $this->placename = $placename;
    }

    /**
     * @param field_type $placedescription
     */
    public function setPlacedescription($placedescription)
    {
        $this->placedescription = $placedescription;
    }

    /**
     * @param field_type $placeaddress
     */
    public function setPlaceaddress($placeaddress)
    {
        $this->placeaddress = $placeaddress;
    }

    /**
     * @param field_type $hashtag
     */
    public function setHashtag($hashtag)
    {
        $this->hashtag = $hashtag;
    }
}