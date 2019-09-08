<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoLoader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace("MinhaBiblioteca");
        $autoloader->registerNamespace("Moip");
        $autoloader->registerNamespace("ZendX");
        $autoloader->setFallbackAutoloader(true);
        return $autoloader;
    }
    
    
  protected function _initConfig() {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        Zend_Registry::set('config', $config);
        $acl = new Zend_Config_Ini(APPLICATION_PATH . '/configs/acl.ini');
        Zend_Registry::set('acl', $acl);
               
        
    
    }
    protected function _initPlugins() {
        $bootstrap = $this->getApplication();
        if ($bootstrap instanceof Zend_Application) {
            $bootstrap = $this;
        }
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        $front->registerPlugin(new MinhaBiblioteca_Plugins_Layout_Layout());
        $front->registerPlugin(new MinhaBiblioteca_Plugins_Equipamento());

        Zend_Controller_Action_HelperBroker::addHelper(new Zend_Controller_Action_Helper_FlashMessenger());
        /*$front->registerPlugin(new SON_Plugins_Placeholders_Categoria());
        $front->registerPlugin(new SON_Plugins_Placeholders_Menu());*/
    }

}

