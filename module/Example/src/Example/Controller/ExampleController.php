<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/21/13
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Example\Controller;

use Example\Model\ExampleModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Json;

class ExampleController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function getDocumentService()
    {
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        return $dm;
    }

    public function insertAction()
    {
        $response = $this->getResponse();

        $exeModel = new ExampleModel();
        $dm = $this->getDocumentService();
        $data = $this->params()->fromPost();

        $result = $exeModel->insert($data, $dm);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }

//    public function selectAction()
//    {
//        $response = $this->getResponse();
//
//        $exeModel = new ExampleModel();
//        $dm = $this->getDocumentService();
//
//
//        $result = $exeModel->select($dm);
//
//        if($result!=null)
//        {
//            return $response->setContent(\Zend\Json\Json::encode(array(
//                'success' => 1,
//                'value'   => $result,
//            )));
//        }
//        else
//        {
//            return $response->setContent(\Zend\Json\Json::encode(array(
//                'success' => 0,
//            )));
//        }
//    }

    public function selectAction() {

        $response = $this->getResponse();

        $dm = $this->getDocumentService();

        $selectModel = new ExampleModel();

        $result = $selectModel->select($dm);
//        var_dump($result[0]['firstname']);

        if ($result != null) {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'   => 1,
                'fristname'     => $result[0]['firstname'],
                'lastname'     => $result[0]['lastname'],
            )));
        }else {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success'   => 0,
            )));
        }
    }

    public function deleteAction() {
        $response = $this->getResponse();
        $dm = $this->getDocumentService();
        $data = $this->params()->fromPost();

        $deleteModel = new ExampleModel();

        $result = $deleteModel->delete($data, $dm);

        if($result)
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
            )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
            )));
        }
    }
}