<?php

namespace Loginpage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Annotation\AnnotationBuilder;

//session
use Zend\Session\Container;

use Loginpage\Model\Login;
use Loginpage\Model\LoginModel;
use Loginpage\Form\LoginForm;

use Application\Document\User;

class LoginpageController extends AbstractActionController
{
    protected $storage;
    protected $authservice;

    public function getLoginCollection()
    {
        return $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
    }

    public function getAuthService()
    {
        if(!$this->authservice)
        {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if(!$this->storage)
        {
            $this->storage = $this->getServiceLocator()
                                  ->get('Loginpage/Model/MyAuthStorage'); //noi de service minh viet
        }
    }

    public function indexAction()
    {
        $form = new LoginForm();
        $form->get('submit')->setValue('Đăng nhập');
       return new ViewModel(array(
           'form' => $form,
       ));
    }

    public function registerAction()
    {
        $form = new LoginForm();
        $form->get('submit')->setValue('Đăng kí');

        $request = $this->getRequest();
        if($request->isPost())
        {
           $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost());

            if($form->isValid())
            {
                //du lieu tu ben form
                $data = $form->getData();

                //ham tuong tac voi csdl ben model
                $mongo = $this->getLoginCollection();

                $model = new LoginModel();
                $user = $model->saveUser($data);

                $mongo->persist($user);
                $mongo->flush();

                return $this->redirect()->toRoute('login');
            }
        }
        return new ViewModel( array('form'=> $form));
    }

    public function loginAction()
    {
        $form = new LoginForm();
        $form->get('submit')->setValue('Đăng Nhập');

        $request = $this->getRequest();

        if($request->isPost())
        {
           $form->setData($this->request->getPost());

            if($form->isValid())
            {
                $data = $form->getData();

                $authService = $this->getServiceLocator()
                                    ->get('doctrine.authenticationservice.odm_default');

                $adapter = $authService->getAdapter();
                $adapter->setIdentityValue($data['username']);
                $adapter->setCredentialValue($data['password']);

                $authResult = $authService->authenticate();

                if($authResult->isValid())
                {
                    $sessionUser = new Container('user');
                    $sessionUser->username = $data['username'];

                    return $this->redirect()->toRoute('success');
                }

                else
                {
                    return new ViewModel( array(
                        'form' => $form,
                        'error' => 'username or password is not valid'
                    ));
                }
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }


    public function successAction()
    {
        return new ViewModel(array());
    }

}