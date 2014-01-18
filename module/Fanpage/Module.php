<?php
/**
 * Created by JetBrains PhpStorm.
 * User: FUHU
 * Date: 12/28/13
 * Time: 10:38 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Fanpage;

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