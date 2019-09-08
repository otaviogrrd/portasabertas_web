<?php
class VendedorController extends Zend_Controller_Action
{
public function preDispatch() { 
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        
        $vendedor = new Application_Model_Vendedor();
        $auth = Zend_Auth::getInstance ();
		$login = $auth->getIdentity();
		if ($login->nivel ==1)
        	$vendedorArray = $vendedor->select()->order('login')->query()->fetchAll();
        else
        	$vendedorArray = $vendedor->select()->where("nivel > 1")->order('login')->query()->fetchAll();
        $paginator = Zend_Paginator::factory($vendedorArray);
        $paginator->setItemCountPerPage(count($vendedorArray));
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
    public function sairAction ()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect("/");
    }
    public function newAction ()
    {
        
        $form = new MinhaBiblioteca_Forms_Vendedor();
        $this->view->form = $form;
        
        $vendedor = new Application_Model_Vendedor();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $data['senha'] = md5($data['senha']);
                unset($data['submit']);
                $vendedor->insert($data);
                $this->_redirect('/vendedor');
            }
        }
    }
    public function editAction ()
    {
        
        $vendedor = new Application_Model_Vendedor();
        $data = $this->_request->getPost();
        $db = $vendedor->getAdapter();
        $rvendedor = $vendedor->find($this->_request->getParam('id_vendedor'))
            ->current()->toArray();
        $form = new MinhaBiblioteca_Forms_Vendedor();
        $id = $this->_getParam('id_vendedor');
        if ($rvendedor['nivel'] == 3){
            $query = $db->quoteInto('select id_barraca from tb_equipamentos where st_validado = 1 and responsavel = ?', $id);
            $rvendedor['id_barraca'] = $db->fetchCol($query);   
        }
        $form->getElement('id_barraca')->setRequired(FALSE);    
        $whereVendedor = $vendedor->getAdapter()->quoteInto('id_vendedor = ?', $id);
        
        
        $form->senha->setRequired(false);
        $form->populate($rvendedor);
        if (isset($data['nivel']) && $data['nivel']==3)
            $form->getElement('id_barraca')->setRequired(TRUE); 
        if ($this->_request->isPost()) {
            if ($form->isValid($data)) {
                unset($data['submit']);
                if(!empty($data['senha']))
                 $data['senha'] = md5($data['senha']);
                if ($data['nivel'] == 3 
                 && (!is_array($data['id_barraca']) 
                   || count($data['id_barraca'])==0)
                 )
                 throw new Exception("Você deve escolher uma barraca ao adicionar um usuário tipo barraca.");
                $vendedorBarraca = new Application_Model_VendedorBarraca();
                if (isset($data['id_barraca']) && count($data['id_barraca'])>0){
                    $barracas = $data['id_barraca'];
                    unset($data['id_barraca']);
                    $vendedorBarraca->delete($whereVendedor);
                    foreach ($barracas as $value) {
                        $vendedorBarraca->insert(array(
                            'id_barraca' => $value,
                            'id_vendedor' => $id
                        ));
                    }
                    
                }
                $vendedor->update($data, $whereVendedor);
                $this->_redirect('/vendedor');
            }
        }
        $this->view->form = $form;
    }
    public function deleteAction ()
    {
        
        $vendedor = new Application_Model_Vendedor();
        $rvendedor = $vendedor->find($this->_request->getParam('id_vendedor'))->current();
        $rvendedor->delete();
        $this->_redirect('/vendedor');
    }
}

