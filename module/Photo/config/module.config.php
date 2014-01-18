<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/25/13
 * Time: 8:30 AM
 * To change this template use File | Settings | File Templates.
 */


return array(
    'controllers' => array(
        'invokables' => array(
            'Photo\Controller\Photo' => 'Photo\Controller\PhotoController',   // <----- Module Controller
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(

            'photos' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/photos[/][:action[/]][:id[/]]',  // <---- url format module/action/id
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',

                    ),
                    'defaults' => array(
                        'controller' => 'Photo\Controller\Photo',  // <--- Defined as the module controller
                        'action'     => 'index',                   // <---- Default action
                    ),
                ),
            ),

        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'photo' => __DIR__ . '/../view',
        ),

    ),
    'doctrine'      => array(
        'authentication'    => array(
            'odm_default'   => array(
                'object_manager'            => 'doctrine.documentmanager.odm_default',
                'identity_class'            => 'Application\Document\User',
                'identity_property'         => 'email',
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