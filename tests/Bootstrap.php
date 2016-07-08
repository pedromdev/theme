<?php

namespace ThemeTest;

use \Zend\Loader\AutoloaderFactory;
use \Zend\Mvc\Service\ServiceManagerConfig;
use \Zend\ServiceManager\ServiceManager;
use \RuntimeException;

error_reporting(E_ALL | E_STRICT);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap {

    protected static $serviceManager;

    public static function init() {
        self::chroot();
        self::initAutoloader();
        // use ModuleManager to load this module and it's dependencies
        $config = include 'config/application.config.php';
        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();
        self::$serviceManager = $serviceManager;
    }

    public static function chroot() {
        $rootPath = dirname(self::findParentPath('module'));
        chdir($rootPath);
    }

    /**
     * 
     * @return ServiceManager
     */
    public static function getServiceManager() {
        return self::$serviceManager;
    }

    protected static function initAutoloader() {
        $vendorPath = self::findParentPath('vendor');

        if (file_exists($vendorPath . '/../init_autoloader.php')) {
            include $vendorPath . '/../init_autoloader.php';
        }
        if (!class_exists('Zend\Loader\AutoloaderFactory')) {
            throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install`');
        }
        AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true,
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                ),
            ),
        ));
    }

    protected static function findParentPath($path) {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir . '/' . $path;
    }

}

Bootstrap::init();