<?php
return array(
    'doctrine' => array(
//        mongo paulo.mongohq.com:10017/facebook -u <user> -p<password>
//        'connection' => array(
//            'odm_default' => array(
//                'server'           => 'paulo.mongohq.com',
//                'port'             => '10017',
//                'connectionString' => 'mongodb://hungnguyen:hung25891@paulo.mongohq.com:10017/facebook',
//                'user'             => 'hungnguyen',
//                'password'         => 'hung25891',
//                'dbname'           => 'facebook',
//                'options'          => array()
//            ),
//        ),
        'connection' => array(
            'odm_default' => array(
                'server'           => 'localhost',
                'port'             => '27017',
                'connectionString' => null,
                'user'             => "root",
                'password'         => "123456",
                'dbname'           => "facebook",
                'options'          => array()
            ),
        ),

        'configuration' => array(
            'odm_default' => array(
                'metadata_cache'     => 'array',

                'driver'             => 'odm_default',

                'generate_proxies'   => true,
                'proxy_dir'          => 'data/DoctrineMongoODMModule/Proxy',
                'proxy_namespace'    => 'DoctrineMongoODMModule\Proxy',

                'generate_hydrators' => true,
                'hydrator_dir'       => 'data/DoctrineMongoODMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',

                'default_db'         => 'facebook',

                'filters'            => array(),  // array('filterName' => 'BSON\Filter\Class'),

                'logger'             => null // 'DoctrineMongoODMModule\Logging\DebugStack'
            )
        ),

        'driver' => array(
            'odm_default' => array(
                'drivers' => array(
                    'Application\Document' => 'sas'
                )
            ),
            'sas' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    'module/Application/src/Application/Document'
                )
            )
        ),

        'documentmanager' => array(
            'odm_default' => array(
                'connection'    => 'odm_default',
                'configuration' => 'odm_default',
                'eventmanager' => 'odm_default'
            )
        ),

        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array()
            )
        ),
    ),
);