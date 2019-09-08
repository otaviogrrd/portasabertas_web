<?php
class BarracaController extends Zend_Controller_Action
{
public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        
        $barracas = new Application_Model_Barracas();
        $barracasArray = $barracas->fetchAll();
        $paginator = Zend_Paginator::factory($barracasArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
    
    public function minhasbarracasAction ()
    {
        $barracas = new Application_Model_Barracas();
        $barracasArray = $barracas->ListaBarracas($this->getRequest()->getCookie('portasabertas'));
        
        
        
        $_SESSION['BARRACAS']=array();
        foreach ($barracasArray as $row){
        	array_push($_SESSION['BARRACAS'],$row->nome);
        }
        $paginator = Zend_Paginator::factory($barracasArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
        $this->view->id_barraca = $this->_getParam('id_barraca',1);
        $this->view->nome = $this->_getParam('nome','Vender');
        $b = $barracasArray->toArray();
        if (count($b)==1){
            $b = $barracasArray[0];
            if ($b['aceita_pagamento'])
                $action = 'meus-produtos-pagamento';
            else
                $action = 'meusprodutos';
            
            $this->_redirect(
                $this->view->url(
                    array(
                        'controller' => 'produtos',
                        'action' => $action,
                        'id_barracas' => $b['id_barraca']
                    )
                )
            );
            
        }
    }
    
    
    
    
    public function newAction ()
    {
        require_once 'MinhaBiblioteca/Forms/Barracas.php';
        $form = new MinhaBiblioteca_Forms_Barracas();
        $this->view->form = $form;
        $barracas = new Application_Model_Barracas();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                unset($data['id_barraca']);
                $barracas->insert($data);
                $this->getHelper('flashMessenger')
                     ->addMessage(array('success'=>"Barraca inserida com sucesso."));   
                $this->_redirect('/Barraca');
            }
        }
    }
    
    
	public function editAction() {
		
        $barracas = new Application_Model_Barracas();
        
		$rbarracas = $barracas->find($this->_request->getParam('id_barracas'))->current();
     	
		require_once 'MinhaBiblioteca/Forms/Barracas.php';
        $form = new MinhaBiblioteca_Forms_Barracas();
        
		$form->populate($rbarracas->toArray());
		if ($this->_request->isPost()) {
			$data = $this->_request->getPost();
			if ($form->isValid($data)) {
				unset($data['submit']);
				//$data['dt_modifica_etapa']= date('Y-m-d h:i:s');
				$barracas->update($data, 'id_barraca=' . (int) $this->_getParam('id_barracas'));
                $this->getHelper('flashMessenger')
                     ->addMessage(array('success'=>"Barraca atualizada com sucesso."));   
				$this->_redirect('/Barraca');
			}
		}
		$this->view->form = $form;
	}
    

public function deleteAction() {
		$id_barracas = $this->_request->getParam('id_barracas');
        $model = new Application_Model_Barracas();
		$barracas = $model->find($id_barracas)->current();
		// Colocar verifica��o de relacionamento antes de excluir
		$barracas->delete();
        $this->getHelper('flashMessenger')
             ->addMessage(array('success'=>"Barraca apagada com sucesso."));   
		$this->_redirect('/Barraca');		
	}
	
}
