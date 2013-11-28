<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Loginpage\Controller\Loginpage' => 'Loginpage\Controller\LoginpageController',   // <----- Module Controller
            'Loginpage\Controller\Success'   => 'Loginpage\Controller\SuccessController'
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
               'login' => array(
                    'type'    => 'segment',
                    'options' => array(
                    'route'    => '/login[/:action][/:id]',  // <---- url format module/action/id
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Loginpage\Controller\Loginpage',  // <--- Defined as the module controller
                        'action'     => 'index',                   // <---- Default action
                        ),
                    ),
               ),
               'success' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'    => '/success[/:action][/:id]',  // <---- url format module/action/id
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ),
                        'defaults' => array(
                            'controller' => 'Loginpage\Controller\Success',  // <--- Defined as the module controller
                            'action'     => 'index',                   // <---- Default action
                        ),
                    ),
               ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'login' => __DIR__ . '/../view',
        ),
    ),
    'doctrine'      => array(
        'authentication'    => array(
            'odm_default'   => array(
                'object_manager'            => 'doctrine.documentmanager.odm_default',
                'identity_class'            => 'Application\Document\User',
                'identity_property'         => 'username',
                'credential_property'       => 'password',
                /*'credential_callable' => function(User $user, $passwordGiven) {
                    return any_check_test($user->getPassword(), $passwordGiven);
                }, /*_*/
            ),
        ),
        // module-name:Users and entity declared : Users\Entity\Users
        'driver'    => array(
            'users' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../src/Application/Document'
                ),
            ),
            'odm_default'   => array(
                'drivers'   => array(
                    'Application\Document' => 'users',
                ),
            ),
        ),
    ),
);