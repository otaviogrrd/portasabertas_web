<?php
class CaixasController extends Zend_Controller_Action
{
public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        

        $caixas = new Application_Model_Caixas();
        $caixasArray = $caixas->relatorioCaixas();
        $paginator = Zend_Paginator::factory($caixasArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
    
    
public function meusprodutosAction ()
    {
    	$id_barracas = $this->_request->getParam('id_barracas');
        
        $produtos = new Application_Model_Produtos();
        $produtosArray = $produtos->fetchAll("barraca like '$id_barracas'");
        $paginator = Zend_Paginator::factory($produtosArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
        $this->view->barraca = $id_barracas;
    }
    
    public function newAction ()
    {
        
        $form = new MinhaBiblioteca_Forms_Caixas();
        $this->view->form = $form;
       

        $caixas = new Application_Model_Caixas();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                $caixas->insert($data);
                $this->_redirect('/Caixas');
            }
        }
    }
    
	public function editAction() {
		

        $caixas = new Application_Model_Caixas();
        
		$rcaixas = $caixas->find($this->_request->getParam('id_caixas'))->current();
     	
		
        $form = new MinhaBiblioteca_Forms_Caixas();
        
		$form->populate($rcaixas->toArray());
		if ($this->_request->isPost()) 
		{
			$data = $this->_request->getPost();
			
			unset($data['submit']);
			$caixas->update($data, 'id_caixa=' . (int) $this->_getParam('id_caixas'));
			$this->_redirect('/Caixas');
		
		}
		
		$this->view->form = $form;
	}

public function deleteAction() {
		$id_caixas = $this->_request->getParam('id_caixas');
		

        $caixas = new Application_Model_Caixas();
		$rcaixas = $caixas->find($this->_request->getParam('id_caixas'))->current();
		// Colocar de relacionamento antes de excluir
		$rcaixas->delete();
		$this->_redirect('/Caixas');		
	}

public function fecharAction() {
        $creditos = new Application_Model_Creditos();
        $id_caixa = $this->_request->getParam('id_caixa');
        $id_vendedor = $this->_request->getParam('id_vendedor');
        $user = Zend_Auth::getInstance()->getIdentity();
        $model = new Application_Model_FechamentoCaixa();
        $idFechamentoCaixa = $model->insert(
            array(
                'id_responsavel_fechamento' => $user->id_vendedor,
            )
        );
		$creditos->update(
            array(
                'status' => 0,
                'id_fechamento_caixa' => $idFechamentoCaixa
            ),
            array(
                $creditos->getAdapter()->quoteInto('id_caixa = ?', $id_caixa),
                $creditos->getAdapter()->quoteInto('id_vendedor = ?', $id_vendedor),
                $creditos->getAdapter()->quoteInto('id_fechamento_caixa is null')
            )
        );
        $modelFCFP = new Application_Model_FechamentoCaixaFormaPagamento();
        $post = $this->getRequest()->getPost();
        $formaPagamento = array(
            '1' => $post['pgcredito'],
            '2' => $post['pgdebito'],
            '3' => $post['pgdinheiro'],
            '4' => $post['pgcheque']
        );
        $esperado = array(
            '1' => 0 + (float) $post['Esperado']['Cartao de Credito'],
            '2' => 0 + (float) $post['Esperado']['Cartao de Debito'],
            '3' => 0 + (float) $post['Esperado']['Dinheiro'],
            '4' => 0 + (float) $post['Esperado']['Cheque'],
        );

        foreach ($formaPagamento as $id_forma_pagamento => $valor) {
            $modelFCFP->insert(
                array(
                    'id_fechamento_caixa'=>$idFechamentoCaixa,
                    'id_forma_pagamento' =>$id_forma_pagamento,
                    'nu_obtido' => (float) $valor,
                    'nu_esperado' => (float) $esperado[$id_forma_pagamento]
                )
            );
        }
        $this->getHelper('flashMessenger')
                             ->addMessage(array('success'=>'Caixa Fechado com sucesso!'));
		
        $this->_redirect("/Relatorios/receitacaixausuario");
		}    
	
}

