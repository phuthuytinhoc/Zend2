<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/17/13
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */


namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="video") */
class Video
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $videoid;

    /** @ODM\Field(type="string") */
    private $albumid;

    /** @ODM\Field(type="string") */
    private $videodescription;

    /** @ODM\Field(type="string") */
    private $videotype;


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
    public function getVideoid()
    {
        return $this->videoid;
    }

    /**
     * @return the $albumid
     */
    public function getAlbumid()
    {
        return $this->albumid;
    }

    /**
     * @return the $videodescription
     */
    public function getVideodescription()
    {
        return $this->videodescription;
    }

    /**
     * @return the $videotype
     */
    public function getVideotype()
    {
        return $this->videotype;
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
     * @param field_type $videoid
     */
    public function setVideoid($videoid)
    {
        $this->videoid = $videoid;
    }

    /**
     * @param field_type $albumid
     */
    public function setAlbumid($albumid)
    {
        $this->albumid = $albumid;
    }

    /**
     * @param field_type $videodescription
     */
    public function setVideodescription($videodescription)
    {
        $this->videodescription = $videodescription;
    }

    /**
     * @param field_type $videotype
     */
    public function setVideotype($videotype)
    {
        $this->videotype = $videotype;
    }


}