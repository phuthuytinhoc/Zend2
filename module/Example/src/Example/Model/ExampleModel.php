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

        $document = $dm->createQueryBuilder('Application\Document\User')
            ->insert()
            ->field('firstname')->set($firstname)
            ->field('lastname')->set($lastname)
            ->getQuery()
            ->execute();

        if(isset($document))
            return true;
        else
            return false;
    }

    public function select($dm) {
        $document = $dm->createQueryBuilder('Application\Document\User')
            ->multiple(true)
            ->getQuery()
            ->execute();
        $docs = array();
        //Neu lay ra một giá trị duy nhất thì ko cần foreach để getLastname()...
        //nhưng nếu dùng execute thì phải foreach để lấy giá trị vì nó có nhiều dữ liệu trong đó
//        ví dụ như
//      foreach($document as $doc)
//      {
//          echo $doc->getLastname();
//      }

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
}