<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/21/13
 * Time: 4:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Example\Model;

class ExampleModel
{
    public function insert($data, $dm)
    {
        $firstname = $data['firstname'];
        $lastname  = $data['lastname'];
        $userid     = $data['userid'];

        $document = $dm->createQueryBuilder('Application\Document\User')
            ->insert()
            ->field('firstname')->set($firstname)
            ->field('lastname')->set($lastname)
            ->field('userid')->set($userid)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;
    }

//    public function select($dm)
//    {
//        $document = $dm->createQueryBuilder('Application\Document\User') //select trong bang User
//            ->getQuery()
////            ->field('userid')->equals('123456') -> so sanh lay tat ca cac truong userid=123456
////            ->execute(); // ra danh sach nhieu gia tri
//            ->getSingleResult(); //lay ra mot gia tri duy nhat
//
//
//        if(isset($document))
//        {
//            $result =$document->getLastname(). $document->getFirstname() ;
//            return $result;
//        }
//        else
//            return null;
//
//    }

    public function select($dm) {
        $document = $dm->createQueryBuilder('Application\Document\User')
            ->multiple(true)
            ->getQuery()
            ->execute();

        $docs = array();

        if (isset($document)) {
            foreach ($document as $doc) {
                $docs[] = array(
                    'firstname' => $doc->getFirstname(),
                    'lastname'  => $doc->getLastname(),
                );
            }

            return $docs;
        }else {
            return null;
        }
    }

    public function delete($data, $dm) {
        $userid     = $data['userid'];

        $document = $dm->createQueryBuilder('Application\Document\User')
            ->remove()
            ->field('userid')->equals($userid)
            ->getQuery()
            ->execute();

        if (isset($document)) {
            return true;
        }else {
            return false;
        }
    }
}