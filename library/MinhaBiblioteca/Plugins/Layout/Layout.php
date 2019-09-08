<?php  
class MinhaBiblioteca_Plugins_Layout_Layout extends Zend_Controller_Plugin_Abstract{
	
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
    	//$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		//$userAgent = $bootstrap->getResource('useragent');
		//$device=$userAgent->getDevice();
		$layout = new Zend_Layout;
		//$layout = Zend_Layout::getMvcInstance();
		//if ($device->getType() == 'mobile')
	      //  $layout->setLayout("layout")->setLayoutPath(APPLICATION_PATH .'/views/layouts');
	    //else
	    $layout->setLayout("layout")->setLayoutPath(APPLICATION_PATH .'/views/mobile-layouts');
		Zend_Registry::set('module',$request->getModuleName());
        Zend_Registry::set('controller',$request->getControllerName());

    }
}


?>