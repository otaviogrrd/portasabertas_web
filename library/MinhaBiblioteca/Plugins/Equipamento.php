<?php  
class MinhaBiblioteca_Plugins_Equipamento extends Zend_Controller_Plugin_Abstract{
	
    private $_skipPages = array(
        array(
            'action' => 'index',
            'controller' => 'index'
        ),
        array(
            'controller' => 'equipamentos',
            'action' => 'solicitar-cadastro'
        ),
    );

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        return;
        if ($this->_skipPages($request))
            return;
    	
        $cookie = $request->getCookie('portasabertas');
        if ($cookie == NULL){
            $erro = 'Seu equipamento não está cadastrado.';
        }else{
            try{
                $model = new Application_Model_Equipamentos();
                $model->verificarEquipamentoCadastrado($cookie, TRUE);
            }catch(Exception $e){
                $erro = $e->getMessage();
            }
        }
        if (isset($erro) && $erro != ''){
            $request->setControllerName('index');
            $request->setActionName('index');
            $fm = new Zend_Controller_Action_Helper_FlashMessenger();
            $fm->addMessage(
                array(
                    'error' => $erro
                )
            );
            if (Zend_Auth::getInstance()->hasIdentity()){
                Zend_Auth::getInstance()->clearIdentity();
            }
        }
    }
    /**
     * Verifica quais páginas não devem receber validação de token (cookie)
     * @param Request $request
     * @return boolean
     */
    private function _skipPages($request){
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        foreach($this->_skipPages as $value)
            if ($value['action'] == $action && $value['controller'] == $controller)
                return true;
        return false;
    }
}


?>