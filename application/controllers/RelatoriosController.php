<?php
class RelatoriosController extends Zend_Controller_Action
{
public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        $modelSaidas = new Application_Model_Saidas();
        $modelCreditos = new Application_Model_Creditos();
        $dados = array();
        $tmp = $modelSaidas->getDatasComSaidas();
        foreach ($tmp as $key => $value) 
            $dados[$value['label']]['saidas'] = $value['code'];
        $tmp = $modelCreditos->getDatasComCreditos();
        foreach ($tmp as $key => $value) 
            $dados[$value['label']]['creditos'] = $value['code'];

        $this->view->dados = $dados;

    }
    
   public function receitaAction ()
    {
		
        $relatorios = new Application_Model_Creditos();
        $busca=$this->_request->getParam('filtro');
    	if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
				$busca=$matches[1];
				}
	    $relatoriosArray = $relatorios->relatorioReceita($busca);
		$paginator = Zend_Paginator::factory($relatoriosArray);
	    $paginator->setItemCountPerPage(150);
		$paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }

   public function receitacaixaAction ()
    {
		
        $relatorios = new Application_Model_Creditos();
		$relatoriosArray = $relatorios->relatorioReceitaCaixa($this->_request->getParam('filtro'));
		$paginator = Zend_Paginator::factory($relatoriosArray);
	    $paginator->setItemCountPerPage(1024);

		$paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
        if(preg_match("/CAIXA\W+(\d+)/",$this->_request->getParam('filtro'),$matches))
        	$this->view->caixa=$matches[1];
        if(preg_match("/VENDEDOR\W+(\d+)/",$this->_request->getParam('filtro'),$matches))
        	$this->view->vendedor=$matches[1];
    }
   public function receitacaixausuarioAction ()
    {
		
        $relatorios = new Application_Model_Creditos();
		$relatoriosArray = $relatorios->relatorioFechamentoCaixa($this->_request->getParam('filtro'));
		$paginator = Zend_Paginator::factory($relatoriosArray);
	    $paginator->setItemCountPerPage(1024);

		$paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
        if(preg_match("/CAIXA\W+(\d+)/",$this->_request->getParam('filtro'),$matches))
        	$this->view->caixa=$matches[1];
    }
   public function receitahoraAction ()
    {
		
        $relatorios = new Application_Model_Creditos();
		$relatoriosArray = $relatorios->relatorioReceitaHora($this->_request->getParam('filtro'));
		$paginator = Zend_Paginator::factory($relatoriosArray);
	    $paginator->setItemCountPerPage(1024);

		$paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
  public function transacoesAction ()
    {
    	$relatorios = new Application_Model_Saidas();
    	$busca=$this->_request->getParam('filtro');
    	if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
				$busca=$matches[1];
				}
        $relatoriosArray = $relatorios->relatorioSaida($busca);
        $paginator = Zend_Paginator::factory($relatoriosArray);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
  public function transacoesbarracaAction ()
    {
    	$auth = Zend_Auth::getInstance ();
		$login = $auth->getIdentity();
    	$permissoes=array(1,2,4);
		if($auth->hasIdentity()){
	        $relatorios = new Application_Model_Saidas();
	        if (isset ($_REQUEST['filtro']))
	        	$busca=$_REQUEST['filtro'];
	        else
	    		$busca=$this->_request->getParam('filtro');
	    	$this->view->filtro=$busca;
	    	if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
					$busca=$matches[1];
					}
			if ($login->nivel == 1 || ($login->nivel !=1 && preg_match("/\d{8}/",$busca))){
		        $relatoriosArray = $relatorios->relatorioSaidaBarraca($busca);
		        $paginator = Zend_Paginator::factory($relatoriosArray);
		        $paginator->setItemCountPerPage(1024);
		        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
		        $this->view->paginator = $paginator;
				}
			}
    }
    
  public function transacoeshoraAction ()
    {
        $relatorios = new Application_Model_Saidas();
    	$busca=$this->_request->getParam('filtro');
    	if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
				$busca=$matches[1];
				}
        $relatoriosArray = $relatorios->relatorioSaidaHora($busca);
        $paginator = Zend_Paginator::factory($relatoriosArray);
        $paginator->setItemCountPerPage(1024);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    } 
   public function receitamoedaAction ()
    {
		
        $relatorios = new Application_Model_Creditos();
		$relatoriosArray = $relatorios->relatorioReceitaMoeda($this->_request->getParam('filtro'));
		$paginator = Zend_Paginator::factory($relatoriosArray);
	    $paginator->setItemCountPerPage(1024);

		$paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }      
  public function saidaprodutosAction ()
    {
        $barracas = new Application_Model_Barracas();
        $barracasArray = $barracas->fetchAll();
        $this->view->saidasprodutosArray = $barracasArray;
    	
    }
    
 public function vendasbarracaAction ()
    {
    	$id_barraca = $this->_request->getParam('id_barracas');
    	$filtro = $this->getRequest()->getParam('id_cartao');
        
    	if(preg_match("/\-?\d+/",$id_barraca) && $filtro != ''){

	        $saidasprodutos = new Application_Model_Saidas();
	        $saidasprodutosArray = $saidasprodutos->relatorioporbarraca($id_barraca,$filtro);
	        $paginator = Zend_Paginator::factory($saidasprodutosArray);
	        $paginator->setItemCountPerPage(50);
	        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
	        $this->view->paginator = $paginator;
	        $barracas = new Application_Model_Barracas();
            if ($id_barraca != 0){
	           $barraca = $barracas->find($id_barraca)->current();
               if (is_null($barraca))
                    throw new Exception('Barraca não encontrada.');
    	        $this->view->barraca = $barraca->nome;
    	        $this->view->id_barraca = $id_barraca;
            }
	    }
    }
    
    
}
