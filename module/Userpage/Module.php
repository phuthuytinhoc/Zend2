<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 11/22/13
 * Time: 3:31 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Userpage;

class Module
{
    public function getAutoloaderConfig()
    {
        return array('Zend\Loader\StandardAutoloader' =>
            array('namespaces' =>
                array(
                    __NAMESPACE__ => __DIR__ . '/src/'. __NAMESPACE__,),
                ),
        );
    }

    public function getConfig()
    {
        return include __DIR__. '/config/module.config.php';
    }

}