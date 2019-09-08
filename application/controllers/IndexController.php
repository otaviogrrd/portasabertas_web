<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
    	if (Zend_Auth::getInstance ()->hasIdentity()){
    		$this->render('empty');
    		return;
    	}

    	$form = new MinhaBiblioteca_Forms_Login();
		$this->view->form = $form;

		if ($this->_request->isPost()) {
			$data = $this->_request->getPost();
            if ($this->getRequest()->getParam('descadastrar') == 'descadastrar'){
                    if(isset($_COOKIE['portasabertas'])){
                        unset($_COOKIE['portasabertas']);
                        setcookie('portasabertas', '', time() - 3600);
                        $this->getHelper('flashMessenger')
                             ->addMessage(array('alert'=>"Seu computador foi descadastrado, para acessar o sistema será necessário recadastrar."));   
                    }
                    return;
            }
			if ($form->isValid($data)) {
				$db = Zend_Registry::get('config');
				$dbAdapter = Zend_Db_Table::getDefaultAdapter();

				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter,'tb_vendedores','login','senha','md5(?)');

				$authAdapter->setIdentity(($data['nome']))->setCredential(($data['senha']));
				$result = $authAdapter->authenticate();
				if($result->isValid()){
						
					$auth = Zend_Auth::getInstance();
					$data = $authAdapter->getResultRowObject(null,'senha');
					$auth->getStorage()->write($data);
					$login = $auth->getIdentity();
					switch ($login->nivel){
						case 3:
							$this->_redirect("/barraca/minhasbarracas");
							break;
						case 2:
							$this->_redirect("/cartao");
							break;
						case 1:
						case 4:
							$this->_redirect("/vendedor");
							break;
						}
					}
				else{
					$this->getHelper('flashMessenger')
                         ->addMessage(array('error'=>"Não foi possivel autenticar"));	
				}
			}
		}
    }

    public function administrarAction(){
        set_time_limit(0);
    	$login = Zend_Auth::getInstance()->getIdentity();

    	if ($login->nivel != 1)
    		$this->_redirect('index');
    	$model = new Application_Model_Auditoria();
    	$this->view->form = $form = new MinhaBiblioteca_Forms_Preparar();
    	if ($this->_request->isPost()) {
    		$data = $this->_request->getPost();
			if ($form->isValid($data)) {
				$form->cartoes->receive();
				$cartoes = $form->cartoes->getFileName();
				
				if (count($cartoes) == 0){
					//limpando os dados da base
					$this->view->log = $model->limpar(
                        $login,
                        array(134, 139,454,455),
						FALSE);
					$this->view->resetCartoes = $model->resetCartoes();	

				}else{
					//limpando os dados da base
					$this->view->log = $model->limpar($login, $login->id_vendedor,TRUE);
					//inserindo os novos cartões
					$modelCartao = new Application_Model_Cartao();
					$this->view->inclusao = $modelCartao->inserirCartoes($cartoes);
						
				}
				
				$this->getHelper('flashMessenger')
                     ->addMessage(array('success'=>"Sistema limpo com sucesso."));
			}else{
                $this->getHelper('flashMessenger')
                     ->addMessage(array('error'=>"Verifique o arquivo informado."));
			}
    	}
	}

}

