<?php
class VendasController extends Zend_Controller_Action
{
public function preDispatch() {
        if (! Zend_Auth::getInstance ()->hasIdentity ())
            $this->_redirect ( '/' );
    
    }
    public function indexAction ()
    {
        // action body
    }
    public function confirmavendaAction ()
    {
        $compra = $_POST['compra'];
        $id_barraca = $_POST['id_barraca'];
        $valor = $_POST['valor'];
        $produto = $_POST['produto'];
        $preco_final = 0;
        $i = 0;
        while ($i < count($_POST['produto'])) {
            $preco_final = $valor[$i] * $compra[$i] + $preco_final;
            $i ++;
        }
        $this->view->valor = $preco_final;
        $this->view->barraca = $id_barraca;
        $this->view->compra = $_POST['compra'];
        $this->view->valor = $_POST['valor'];
        $this->view->produto = $_POST['produto'];
    }
    public function salvavendaAction ()
    {
        $data['id_cartao']= $_POST['busca'];
        $auth = Zend_Auth::getInstance();
       $login = $auth->getIdentity();
       $data['id_vendedor'] = $login->id_vendedor;
       $id_barraca = $this->_request->getParam('id_barraca');
        $i=0;
        while ($i < count($_POST['produto'])) {
                if (is_numeric($_POST['qtd'][$i]) && $_POST['qtd'][$i] > 0){
                    $data['id_produto']=$_POST['produto'][$i];
                    $data['qtd']=$_POST['qtd'][$i];
                    $data['valor']=($_POST['valor'][$i] * $_POST['qtd'][$i]);
                    $venda = new Application_Model_Vendas();
                    $venda->insert($data);
                    }
                $i ++;
                }
        $_SESSION['ALERTA'][]="Registro de venda realizado com sucesso.";
          $this->getHelper('flashMessenger')
               ->addMessage(array('success'=>"Registro de venda realizado com sucesso.")); 
        $this->_redirect('/Produtos/meusprodutos/id_barracas/'.$id_barraca);
        #$this->_redirect('/relatorios/vendasbarraca/id_barracas/'.$id_barraca);
    }
	
    public function salvavendanegativoAction ()
    {
        $data['id_cartao']= $_POST['busca'];
        $auth = Zend_Auth::getInstance();
       $login = $auth->getIdentity();
       $data['id_vendedor'] = $login->id_vendedor;
       $id_barraca = $this->_request->getParam('id_barraca');
        $i=0;
        while ($i < count($_POST['produto'])) {
                if (is_numeric($_POST['qtd'][$i]) && $_POST['qtd'][$i] > 0){
                    $data['id_produto']=$_POST['produto'][$i];
                    $data['qtd']=$_POST['qtd'][$i];
                    $data['valor']=($_POST['valor'][$i] * $_POST['qtd'][$i]);
                    $venda = new Application_Model_Vendas();
                    $venda->insert($data);
                    }
                $i ++;
                }
        $_SESSION['ALERTA'][]="Registro de venda realizado com sucesso.";
          $this->getHelper('flashMessenger')
               ->addMessage(array('success'=>"Registro de venda realizado com sucesso.")); 
        $this->_redirect('/Produtos/meus-produtos-negativo/id_barracas/'.$id_barraca);
    }
    public function salvavendachurrascoAction ()
    {
        #id do operador
        $auth = Zend_Auth::getInstance();
        $login = $auth->getIdentity();
        $caixas = new Application_Model_Caixas();
        $caixa = $caixas->fetchrow("ip = '".$this->getRequest()->getServer('REMOTE_ADDR')."'");
        if (is_numeric($caixa->id_caixa)){
            $PG=array(1=>'pgcredito',2=>'pgdebito',3=>'pgdinheiro',4=>'pgcheque');
            foreach ($PG as $id_forma => $forma){
                if ($_POST[$forma] > 0){
                    $cdata['id_caixa']=$caixa->id_caixa;
                    $cdata['valor']=$_POST[$forma];
                    $cdata['id_estorno']="0";
                    $cdata['id_cartao']= $_POST['busca'];
                    
                    if ($cdata['id_cartao']=='' ||$cdata['id_cartao']==null)
                        $cdata['id_cartao'] = $login->id_vendedor * -1;
                    $cdata['id_forma']=$id_forma;
                    $cdata['status']=1;

                    $cdata['id_vendedor'] = $login->id_vendedor;
                    
                    $entrada = new Application_Model_Creditos();
                    $entrada->insert($cdata);
                    }
                }
            $data['id_cartao']= $_POST['busca'];
            if ($data['id_cartao']=='' ||$data['id_cartao']==null)
                $data['id_cartao'] = $login->id_vendedor * -1;
            $auth = Zend_Auth::getInstance();
            $login = $auth->getIdentity();
            $data['id_vendedor'] = $login->id_vendedor;
            $id_barraca = $this->_request->getParam('id_barraca');
            $i=0;
            while ($i < count($_POST['produto'])) {
                if (is_numeric($_POST['qtd'][$i]) && $_POST['qtd'][$i] > 0){
                    $data['id_produto']=$_POST['produto'][$i];
                    $data['qtd']=$_POST['qtd'][$i];
                    $data['valor']=($_POST['valor'][$i] * $_POST['qtd'][$i]);
                    $venda = new Application_Model_Vendas();
                    $venda->insert($data);
                    }
                $i ++;
                }
          $this->getHelper('flashMessenger')
               ->addMessage(array('success'=>"Registro de venda realizado com sucesso.")); 
            
            }
        else{
      $this->getHelper('flashMessenger')
           ->addMessage(array('error'=>"Endereço IP do Caixa não cadastrado (".$this->getRequest()->getServer('REMOTE_ADDR').")!")); 
            }
    $this->_redirect('/Produtos/meus-produtos-pagamento/id_barracas/'.$id_barraca);
        
    }
    public function estornoAction ()
    {
        $auth = Zend_Auth::getInstance();
        $login = $auth->getIdentity();
        $id_vendedor = $login->id_vendedor;
        $id_venda = $this->_request->getParam('id_venda');
        $id_barraca = $this->_request->getParam('id_barraca');
        $venda = new Application_Model_Vendas();
        $data= array('status'=> 0);
        $venda->update($data, 'id_venda=' .$id_venda, 'id_vendedor='.$id_vendedor);
        ?><script type="text/javascript">alert('Venda estornada com sucesso.')</script><?php 
        $this->getHelper('flashMessenger')
               ->addMessage(array('success'=>"Venda estornada com sucesso.")); 
        $this->_redirect('/relatorios/vendasbarraca/id_barracas/'.$id_barraca);
    }
    public function estornounicoAction ()
    {
        $auth = Zend_Auth::getInstance();
        $login = $auth->getIdentity();
        $id_vendedor = $login->id_vendedor;
        $id_venda = $this->_request->getParam('id_venda');
        $id_barraca = $this->_request->getParam('id_barraca');
        $qtd = $this->_request->getParam('qtd');
        $valor = $this->_request->getParam('valor');
		$qtd = $qtd - 1;
        $valor = $valor * $qtd;
        $venda = new Application_Model_Vendas();
        $venda->update(
		array( 'qtd'=> $qtd   ,
               'valor'=> $valor ), 'id_venda=' .$id_venda, 'id_vendedor='.$id_vendedor);
        ?><script type="text/javascript">alert('Venda estornada com sucesso.')</script><?php 
        $this->getHelper('flashMessenger')
               ->addMessage(array('success'=>"Venda estornada com sucesso.")); 
        $this->_redirect('/relatorios/vendasbarraca/id_barracas/'.$id_barraca);
    }
}

