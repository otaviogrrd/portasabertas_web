<?php
class EquipamentosController extends Zend_Controller_Action
{
public function preDispatch() {

		if (!Zend_Auth::getInstance()->hasIdentity() && $this->getRequest()->getActionName() !='solicitar-cadastro')
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        
        $equipamentos = new Application_Model_Equipamentos;
        $equipamentosArray = $equipamentos->relatorioEquipamentos();
        $modelBarracas = new Application_Model_Barracas();
        $this->view->barracas = $modelBarracas->fetchPair();
        $paginator = Zend_Paginator::factory($equipamentosArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
    
    public function minhasbarracasAction ()
    {
        
        $equipamentos = new Application_Model_Equipamentos;
        $equipamentosArray = $equipamentos->fetchAll();
        $paginator = Zend_Paginator::factory($equipamentosArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
        $this->view->id_barraca = $this->_getParam('id_barraca',1);
        $this->view->nome = $this->_getParam('nome','Vender');
    }
    
    
    
    
    public function newAction ()
    {
        require_once 'MinhaBiblioteca/Forms/Equipamentos.php';
        $form = new MinhaBiblioteca_Forms_Equipamentos();
        $this->view->form = $form;
       
        $equipamentos = new Application_Model_Equipamentos;
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $equipamentos->insert($data);
                $this->_redirect('/Equipamentos');
            }
        }
    }
    
    
	public function editAction() {
		
        $equipamentos = new Application_Model_Equipamentos;
        
		$rprodutos = $equipamentos->find($this->_request->getParam('id_equipamento'))->current();
		require_once 'MinhaBiblioteca/Forms/Equipamentos.php';
        $form = new MinhaBiblioteca_Forms_Equipamentos();
        
		$form->populate($rprodutos->toArray());
		if ($this->_request->isPost()) {
			$data = $this->_request->getPost();
			if ($form->isValid($data)){
				unset($data['submit']);
				//$data['dt_modifica_etapa']= date('Y-m-d h:i:s');
				$equipamentos->update($data, 'id_equipamento=' . (int) $this->_getParam('id_equipamento'));
					$this->_redirect('/Equipamentos');
				}
		}
		$this->view->form = $form;
	}
    

public function deleteAction() {
		$id_equipamento = $this->_request->getParam('id_equipamento');
		
        $equipamentos = new Application_Model_Equipamentos;
		$requipamentos = $equipamentos->find($this->_request->getParam('id_equipamento'))->current();
		// Colocar verificação de relacionamento antes de excluir
		$requipamentos->delete();
		$this->_redirect('/Equipamentos');		
	}
	public function solicitarCadastroAction(){
        $model = new Application_Model_Equipamentos();
        $this->view->form = $form = new MinhaBiblioteca_Forms_Login();
        $form->submit->setLabel('Cadastrar Equipamento');
        if ($this->getRequest()->getCookie('portasabertas')){
            try {
                $model->verificarEquipamentoCadastrado($this->getRequest()->getCookie('portasabertas'));
                $existeEquipamento = TRUE;
            } catch (Exception $e) {
                if ($e->getMessage() != 'Equipamento não cadastrado. Entre em contato com a equipe de TI.'){
                    throw $e;       
                }else{
                    $existeEquipamento = FALSE;
                }
            }
            if ($existeEquipamento){
                $this->getHelper('flashMessenger')
                     ->addMessage(array('error'=>'Sua máquina já está cadastrada.'));
            }
        }else{
            $existeEquipamento = FALSE;
        }


        if (!$existeEquipamento){            
            if ($this->_request->isPost()) {
                $data = $this->_request->getPost();
                if ($form->isValid($data)) {
                    $db = Zend_Registry::get('config');
                    $dbAdapter = Zend_Db_Table::getDefaultAdapter();
                    $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter,'tb_vendedores','login','senha','md5(?)');
                    $authAdapter->setIdentity(($data['nome']))->setCredential(($data['senha']));
                    $result = $authAdapter->authenticate();
                    
                    if($result->isValid()){
                        $data = array(
                            'ip' => $this->getRequest()->getClientIp(),
                            'descricao' => $this->getRequest()->getHeader('User-Agent'),
                            'responsavel' => current($authAdapter->getResultRowObject('id_vendedor')),
                            'st_validado' => 0
                        );
                        $id = $model->insert($data);
                        $data['id_equipamento'] = $id;
                        $hash = md5(print_r($data, true));
                        $model->update(
                            array('mac' => $hash),
                            $model->getAdapter()->quoteInto('id_equipamento = ?', $id)
                        );
                        $this->getResponse()->setRawHeader(
                            new Zend_Http_Header_SetCookie(
                                'portasabertas', 
                                $hash, 
                                $this->getRequest()->getHttpHost(),
                                '/'
                            )
                        );
                        $this->_helper
                             ->getHelper('flashMessenger')
                             ->addMessage(array('success'=>'Sua solicitação foi processada com sucesso, procure o administrador do sistema.'));
                    }else{
                        $this->getHelper('flashMessenger')
                             ->addMessage(array('error'=>'Usuário ou senhas inválidos.'));
                    }
                }
            }
        }
    }
    public function ativacaoAction(){
        if ($this->getRequest()->getParam('id') == null){
            $this->_helper->json((object) array('status'=>0, 'msg'=>'É necessário informar um eqipamento.'));    
        }
        try {
            $model = new Application_Model_Equipamentos();
            $result = $model->ativarEquipamento($this->getRequest()->getParam('id'));
            $this->_helper->json((object) array('status'=>1, 'msg'=>$result));    
        } catch (Exception $e) {
            $this->_helper->json((object) array('status'=>0, 'msg'=>$e->getMessage()));    
        }
        
    }
    public function escolherBarracaAction(){
        $equipamento = $this->getRequest()->getParam('barraca');
        if ($equipamento == null){
            $this->_helper->json((object) array('status'=>0, 'msg'=>'É necessário informar um eqipamento.'));    
        }
        $barraca = $this->getRequest()->getParam('id');
        if ($barraca == null){
            $this->_helper->json((object) array('status'=>0, 'msg'=>'É necessário informar uma barraca.'));    
        }
        try {
            $model = new Application_Model_Equipamentos();
            $result = $model->update(
                array(
                    'id_barraca'=>($barraca == 0) ? null : $barraca
                ),
                $model->getAdapter()->quoteInto('id_equipamento = ?', $equipamento)
            );
            if ($result == 1)
                $this->_helper->json((object) array('status'=>1, 'msg'=>'Equipamento atualizado com sucesso!'));    
            else
                $this->_helper->json((object) array('status'=>0, 'msg'=>'Não foi possível atualizar o equipamento.'));    
        } catch (Exception $e) {
            $this->_helper->json((object) array('status'=>0, 'msg'=>$e->getMessage()));    
        }
        
    }
}

