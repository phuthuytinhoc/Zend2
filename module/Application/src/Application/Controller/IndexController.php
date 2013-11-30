<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Doctrine\ORM\Query\Parser;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Json;
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;

//session
use Zend\Session\Container;

use Application\Model\Login;
use Application\Model\LoginModel;
use Application\Form\LoginForm;

use Application\Model\Register;
use Application\Form\RegisterForm;

use Application\Document\User;

class IndexController extends AbstractActionController
{
    protected $storage;
    protected $authservice;

    public function getDocumentService()
    {
        return $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
    }

    public function getAuthService()
    {
        if(!$this->authservice)
        {
            $this->authservice = $this->getServiceLocator()
                ->get('DoctrineAuthentication');
        }
        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if(!$this->storage)
        {
            $this->storage = $this->getServiceLocator()
                ->get('Application/Model/MyAuthStorage'); //noi de service minh viet
        }
    }

    public function indexAction()
    {
//        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
//        $result = new User();
//        $result = $user = $dm->getRepository('Application\Document\User')->findOneBy(array('email' => 'phuthuytinhoc@yahoo.com' ));
//        $result = $dm->createQueryBuilder('Application\Document\User')
//            ->field('password')->equals('12345')
//            ->getQuery()
//            ->getSingleResult();
//        if($result != null)
//            $result = "khac null";
//        else $result = "bang null";
//        echo 'hungnguyen' .  $result->getLastname() . $result->getFirstname();

        ////////////
        if($this->getAuthService()->hasIdentity())
            return $this->redirect()->toRoute('success');
        else
        {
            //print form Login
            $form = new LoginForm();
            $form->get('submit')->setValue('Đăng nhập');

            return new ViewModel(array(
                'form' => $form,
            ));
        }
    }

//    public function registerrrrAction()
//    {
//        $form = new LoginForm();
//        $form->get('submit')->setValue('Đăng kí');
//
//        $request = $this->getRequest();
//        if($request->isPost())
//        {
//            $login = new Login();
//            $form->setInputFilter($login->getInputFilter());
//            $form->setData($request->getPost());
//
//            if($form->isValid())
//            {
//                //du lieu tu ben form
//                $data = $form->getData();
//                //ham tuong tac voi csdl ben model
//                $mongo = $this->getDocumentService();
//
//                $model = new LoginModel();
//                $user = $model->saveUser($data);
//
//                $mongo->persist($user);
//                $mongo->flush();
//
//                return $this->redirect()->toRoute('login');
//            }
//        }
//        return new ViewModel( array('form'=> $form));
//    }
//
//    public function loginAction()
//    {
//        $form = new LoginForm();
//        $form->get('submit')->setValue('Đăng Nhập');
//
//        $request = $this->getRequest();
//
//        if($request->isPost())
//        {
//            $form->setData($this->request->getPost());
//
//            if($form->isValid())
//            {
//                $data = $form->getData();
//
//                $authService = $this->getServiceLocator()
//                    ->get('doctrine.authenticationservice.odm_default');
//
//                $adapter = $authService->getAdapter();
//                $adapter->setIdentityValue($data['email']);
//                $adapter->setCredentialValue($data['password']);
//
//                $authResult = $authService->authenticate();
//
//                if($authResult->isValid())
//                {
//                    $sessionUser = new Container('user');
//                    $sessionUser->email = $data['email'];
//
//                    return $this->redirect()->toRoute('success');
//                }
//                else
//                {
//                    return $this->redirect()->toRoute('home');
//                }
//            }
//        }
//
//        return new ViewModel(array(
//            'form' => $form,
//        ));
//    }

    public function testAction()
    {
        $email = $this->params()->fromPost('email');
        $password = $this->params()->fromPost('password');

        $authService = $this->getServiceLocator()
            ->get('DoctrineAuthentication');

        $adapter = $authService->getAdapter();
        $adapter->setIdentityValue($email);
        $adapter->setCredentialValue($password);

        $authResult = $authService->authenticate();

        $response = $this->getResponse();
        if($authResult->isValid())
        {
            $this->getAuthService()->getStorage()->write($email);

            $success = '/success';
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 1,
                'path' => $success, )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => 'Tên đăng nhập hoặc mật khẩu không đúng.', )));
        }
    }

    public function registerAction()
    {
        $response = $this->getResponse();

        $data = $this->params()->fromPost();
        $mongo = $this->getDocumentService();

        $model = new LoginModel();
        $rs = $model->saveUser($data, $mongo);

        if($rs ==true )
        {
            $email = $data['email'];
            $this->getAuthService()->getStorage()->write($email);
            $success = '/success';
            return $response->setContent(\Zend\Json\Json::encode(array('success' => 1, 'path' => $success, )));
        }
        else
        {
            return $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0,
                'error' => 'Email này đã được đăng kí, bạn hãy chọn email khác!',)));
        }
    }
}
