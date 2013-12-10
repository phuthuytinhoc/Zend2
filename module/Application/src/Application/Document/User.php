<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 10/16/13
 * Time: 7:18 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(collection="user") */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $firstname;

    /** @ODM\Field(type="string") */
    private $lastname;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\Field(type="string") */
    private $password="";

    /** @ODM\Field(type="string") */
    private $createdtime="";

    /** @ODM\Field(type="string") */
    private $userid="";

    /** @ODM\Field(type="string") */
    private $dob="";

    /** @ODM\Field(type="string") */
    private $address="";

    /** @ODM\Field(type="string") */
    private $quote="";

    /** @ODM\Field(type="string") */
    private $relationship="";

    /** @ODM\Field(type="string") */
    private $school="";

    /** @ODM\Field(type="string") */
    private $work="";

    //******* GET METHOD *******//

    /**
     * @return the $id
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return the $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        $val = null;
        $val= $this->password;
        return $val;
    }

    /**
     * @return the $createdtime
     */
    public function getCreatedtime()
    {
        return $this->createdtime;
    }

    /**
     * @return the $userid
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @return the $dob
     */
    public function getDOB()
    {
        return $this->dob;
    }

    /**
     * @return the $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return the $quote
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @return the $relationship
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @return the $school
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @return the $work
     */
    public function getWork()
    {
        return $this->work;
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
     * @param field_type $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param field_type $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param field_type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param field_type $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param field_type $createdtime
     */
    public function setCreatedtime($createdtime)
    {
        $this->createdtime = $createdtime;
    }

    /**
     * @param field_type $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @param field_type $dob
     */
    public function setDOB($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @param field_type $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param field_type $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * @param field_type $relationship
     */
    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * @param field_type $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
    }

    /**
     * @param field_type $work
     */
    public function setWork($work)
    {
        $this->work = $work;
    }

}