<?php
class CartaoController extends Zend_Controller_Action
{
public function preDispatch() {
	
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        if ($this->_request->getParam('busca') != "") {
            $busca = $this->_request->getParam('busca');
            $busca = preg_replace ('@(\'|"|´|`|;|\(|\))@',"",$busca); 
            if ($busca != ""){
                $cartao = new Application_Model_Cartao();
	        	$cartaoArray = $cartao->pesquisar($busca);
                $creditos = new Application_Model_Creditos();
                foreach ($cartaoArray as $key => $value) {
                    $denarios = $creditos->select()
                                         ->where('id_cartao = ?', $value['id_cartao'])
                                         ->where('id_forma = 5')
                                         ->query()
                                         ->fetchAll();
                    if (count($denarios) > 0){
                        $cartaoArray[$key]['tem_denario'] = true;

                    }
                }
	            $paginator = Zend_Paginator::factory($cartaoArray);
	            $paginator->setItemCountPerPage(1000);
	            $paginator->setCurrentPageNumber(
	            $this->_request->getParam('pagina'));
	            $this->view->paginator = $paginator;
            }
        } 
    }
    
    /*
    Action criada para receber a pagina de carregamento de credito apos a inserção de credito 
    sem o botão de inserir, para evitar erros dos caixas
    */
    public function creditosucessoAction ()
    {
        if ($this->_request->getParam('busca') != "") {
            $busca = $this->_request->getParam('busca');
            $busca = preg_replace ('@(\'|"|´|`|;|\(|\))@',"",$busca); 
            if ($busca != ""){
	            $cartao = new Application_Model_Cartao();
	        	$cartaoArray = $cartao->pesquisar($busca);
                $creditos = new Application_Model_Creditos();
                foreach ($cartaoArray as $key => $value) {
                    $denarios = $creditos->select()
                                         ->where('id_cartao = ?', $value['id_cartao'])
                                         ->where('id_forma = 5')
                                         ->query()
                                         ->fetchAll();
                    if (count($denarios) > 0){
                        $cartaoArray[$key]['tem_denario'] = true;

                    }
                }
	            $paginator = Zend_Paginator::factory($cartaoArray);
	            $paginator->setItemCountPerPage(1000);
	            $paginator->setCurrentPageNumber(
	            $this->_request->getParam('pagina'));
	            $this->view->paginator = $paginator;
            }
        } 
    }

	public function portadorAction ()
    {
		$busca = $this->_request->getParam('id');
		if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
			$busca=$matches[1];
			}
		
        $cartao = new Application_Model_Cartao();
        $cartaoArray = $cartao->fetchAll( "id_cartao = '$busca'","id_cartao DESC");
        //var_dump($cartaoArray->toArray());
        //exit;
        $this->_helper->json->sendJson($cartaoArray->toArray());
    }
	
    public function desativarAction ()
    {
		$id_cartao = $this->_request->getParam('id_cartao');
    	if (preg_match("/0+(\d{8})\d/",$busca,$matches)){
			$busca=$matches[1];
		}
		$saldo = $this->_request->getParam('s');
        
        $venda = new Application_Model_Vendas();
        $auth = Zend_Auth::getInstance();
        $login = $auth->getIdentity();
        $data['id_vendedor'] = $login->id_vendedor;
        $data['id_cartao']= $id_cartao;
        $data['id_produto']= -1;
        $data['qtd']=1;
        $data['valor']=$saldo;
        $venda->insert($data);
        
        $cartao = new Application_Model_Cartao();
        $cartao->update(array('status' => 2,'creditos'=> 0),'id_cartao='.(int) $id_cartao);
        $this->_redirect("/cartao?busca={$data['id_cartao']}&s={$saldo}");
    }
    
    public function inserircreditoAction ()
    {
        #id do cartao
    	
        $cartao = new Application_Model_Cartao();
        $rcartao = $cartao->find($this->_request->getParam('id_cartao'))
            ->current();
        require_once 'MinhaBiblioteca/Forms/Credito.php';
        $form = new MinhaBiblioteca_Forms_Credito();
        $form->populate($rcartao->toArray());
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
			
			$caixas = new Application_Model_Caixas();
			$caixa = $caixas->fetchrow("ip = '".$this->getRequest()->getServer('REMOTE_ADDR')."'");
            if ($caixa == null)
			 throw new Exception("caixa não cadastrado. IP: " . $this->getRequest()->getServer('REMOTE_ADDR'));

			if (is_numeric($caixa->id_caixa)){
				unset($data['creditos']);
                unset($data['confirmar']);
                unset($data['situacao']);
                $data['id_estorno']="0";
                $data['id_cartao'] = $rcartao->id_cartao;
				$data['id_caixa']=$caixa->id_caixa;
				#$data['id_caixa'] = 1; #caixa geral
        		#id do operador
        		$auth = Zend_Auth::getInstance();
        		$login = $auth->getIdentity();
                $data['id_vendedor'] = $login->id_vendedor;
                
                $entrada = new Application_Model_Creditos();
                $entrada->insert($data);
                $_SESSION['ALERTA'][]="Crédito inserido com sucesso!!";
                    $this->getHelper('flashMessenger')
                         ->addMessage(array('success'=>"Crédito inserido com sucesso!!")); 
                $this->_redirect("/cartao/creditosucesso?busca={$data['id_cartao']}");
				}
			else{
				$this->view->mensagem =" Endereço IP do Caixa não cadastrado (".$this->getRequest()->getServer('REMOTE_ADDR').")!";
				}
            }
        }
        $this->view->form = $form;
    }
 public function criarAction ()
    {
    	
        $form = new MinhaBiblioteca_Forms_Cartao();
        $cartao = new Application_Model_Cartao();
        if ($this->_request->isPost()) {
        	$rcartao = $cartao->find($this->_request->getParam('id_cartao'))->current();
			print "<PRE>";
    		var_dump($rcartao->status);
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                unset($data['submit']);
                unset($data['situacao']);
				$data['descricao'] = $data['descricao']." ".$data['sobrenome'];
                unset($data['sobrenome']);
                $data['status'] = 1;
                switch($rcartao->status){
                	case 0:
                		$cartao->update($data, 'id_cartao='.$data['id_cartao'],'status= 0');
                		$this->_redirect('/Cartao/inserircredito/id_cartao/'.$data['id_cartao']);
                		break;
                	case 1:
                		$this->view->mensagem = "ERRO. Este cartão já esta associado a {$rcartao->descricao}.";
                		break;
                	case 2:
                		$this->view->mensagem = "ERRO. Este cartão já foi utilizado por outra pessoa e esta desativado.";
                		break;
                	}
            }
        }
        $this->view->form = $form;
    }
public function rangeAction ()
    {
    	
        $form = new MinhaBiblioteca_Forms_Range();
        $cartao = new Application_Model_Cartao();
        if ($this->_request->isPost()) {
			$data = $this->_request->getPost();
            if ($form->isValid($data)) {
            	$data['creditos']=0;
            	$data['descricao']="DISPONIVEL";
            	$inicio=$data['inicio'];
            	$qtd=$data['qtd'];
            	$prefixo=$data['prefixo'];
            	unset($data['submit'],$data['id_cartao'],$data['inicio'],$data['qtd'],$data['prefixo']);
        		$rcartao = new Application_Model_Cartao();
            	while ($x < $qtd){
					//f ($prefixo != ''){                    #Otávio 2020-15-01 adicionei este código para gerar os cartões comanda
					//	$ini = str_pad($prefixo,8,"0");
					//	$fim = str_pad($prefixo,8,"9");
					//	$novo_cartao=rand($ini,$fim);
					//else{
						$novo_cartao=rand(10000000,99999999);
					//}
            		$cartao_existente = $rcartao->fetchrow("id_cartao = '$novo_cartao'");
            		if (!preg_match("/\d+/",$cartao_existente->id_cartao)){
            			$data['id_cartao']=$novo_cartao; 
						
						//$number = str_pad($x + 1,4,"0", STR_PAD_LEFT);
						//$data['descricao']="Comanda ".$number;
						//$data['status']=1;
            			//$data['tp_cartao']= 1;
						
            			$cartao->insert($data);
            			array_push($cartoes,$novo_cartao);
            			$x++;
            			}
            		}
               					
               $this->getHelper('flashMessenger')
                    ->addMessage(array('success'=>"{$qtd} cartões criados no banco."));   
               $this->_redirect('/cartao/range');
            	}
        	}
        else{
            $cartaoArray = $cartao->fetchAll();
            $paginator = Zend_Paginator::factory($cartaoArray);
            $paginator->setItemCountPerPage(50);
            $paginator->setCurrentPageNumber(
            $this->_request->getParam('pagina'));
            $this->view->paginator = $paginator;
        	}
        $this->view->form = $form;
    }
    public function doacaoAction ()
    {
        
        $form = new MinhaBiblioteca_Forms_Doacao();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
                $auth = Zend_Auth::getInstance();
                $login = $auth->getIdentity();
                $data['id_caixa'] = '1';#caixa geral
                $data['id_vendedor'] = $login->id_vendedor;
            	$data["valor"] = str_replace(',','.',$data["valor"]);
                $data['id_cartao'] = "1";
                unset($data['creditos']);
                unset($data['submit']);
                
                #registra a entrada do credito da doacao
                $entrada = new Application_Model_Creditos();
                $entrada->insert($data);
                
                # registra a doacao na tabela de vendas
                $doacao = new Vendas();
                unset($data['id_forma']); 
                unset($data['id_caixa']);
                /*
        		$Produtos = new Application_Model_Produtos();
        		$Produtos = $Produtos->fetchrow("descricao = 'Doacao'");
        		$data['id_produto'] = (is_numeric($Produtos->id_produtos)? $Produtos->id_produtos:19);*/
                $data['id_produto'] = 18; #somente neste momento, o ideal é buscar o id do produto doacao
                $data['qtd'] = 1;
                $doacao->insert($data);
                $this->_redirect("/cartao?busca={$data['id_cartao']}");
            }
        }
        $this->view->form = $form;
    }
    public function estornoAction ()
    {
        
        $cartao = new Application_Model_Cartao();
        $creditos = new Application_Model_Creditos();
        $saldo = $creditos->getCreditosPorTipo($this->_request->getParam('id_cartao'));
        
        if (count($saldo) == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não há crédito neste cartão."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        $rcartao = $cartao->find($this->_request->getParam('id_cartao'))
            ->current();
        
        $form = new MinhaBiblioteca_Forms_Estorno();
        $form->populate($rcartao->toArray());
        $this->view->form = $form;
        $creditosInseridos = 0;
        $creditosEstornados = 0;
        foreach ($saldo as $formaPagamento => $valor) {
            if ($formaPagamento != 5 && $valor > 0){
                $creditosInseridos = $creditosInseridos + $valor;
            }
            if ($valor < 0){
                $creditosEstornados = $creditosEstornados + $valor;
            }
        }

        if ($creditosInseridos == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não é possível estornar este cartão, pois foi carregado exclusivamente com denários."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($rcartao->creditos - $data['valor'] >= 0) {
                if ($data['valor'] > ($creditosInseridos + $creditosEstornados) ){

                
                    $this->getHelper('flashMessenger')
                         ->addMessage(array('error'=>"Não é possível utilizar o crédito em denários para estorno."));   
                    return;
                }

                if ($form->isValid($data)) {
                    
                    $data = $this->_request->getPost();
                    unset($data['creditos']);
                    unset($data['submit']);
                    unset($data['situacao']);
                    unset($data['documento']);
                    $data['valor'] = $data['valor'] * -1;
                    $data['id_cartao'] = $rcartao->id_cartao;
                    
                    $auth = Zend_Auth::getInstance();
                    $login = $auth->getIdentity();
                    
                    
					$caixas = new Application_Model_Caixas();
					$caixa = $caixas->fetchrow("ip = '".$this->getRequest()->getServer('REMOTE_ADDR')."'");
					$data['id_caixa']=$caixa->id_caixa;
					if (is_numeric($caixa->id_caixa)){
						$data['id_caixa']=$caixa->id_caixa;
						//$data['id_caixa'] = 1; # caixa geral
						$data['id_vendedor'] = $login->id_vendedor;
                    	
                    	$entrada = new Application_Model_Creditos();
                    	$entrada->insert($data);
                        $this->getHelper('flashMessenger')
                             ->addMessage(array('success'=>"Estorno realizado com sucesso."));   
                    	$this->_redirect("/cartao?busca={$data['id_cartao']}");
						}
					else
						{
						$this->getHelper('flashMessenger')
                             ->addMessage(array('error'=>"Caixa não cadastrado (".$this->getRequest()->getServer('REMOTE_ADDR').")!"));   
						}	
                }
            }
            else{
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não foi possível extornar esse valor pois ele ultrapassa o valor de créditos existentes no cartão."));   
            }
        }
        
    }
    public function mesclarAction (){
        
        $cartao = new Application_Model_Cartao();
        $creditos = new Application_Model_Creditos();
        $saldo = $creditos->getCreditosPorTipo($this->_request->getParam('id_cartao'));
        
        if (count($saldo) == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não há crédito neste cartão."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        $rcartao = $cartao->find($this->_request->getParam('id_cartao'))->current();
        
        $form = new MinhaBiblioteca_Forms_Mesclar();
        $form->populate($rcartao->toArray());
        $this->view->form = $form;
        $creditosInseridos = 0;
        $creditosEstornados = 0;
        foreach ($saldo as $formaPagamento => $valor) {
            if ($formaPagamento != 5 && $valor > 0){
                $creditosInseridos = $creditosInseridos + $valor;
            }
            if ($valor < 0){
                $creditosEstornados = $creditosEstornados + $valor;
            }
        }

        if ($creditosInseridos == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não é possível estornar este cartão, pois foi carregado exclusivamente com denários."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($rcartao->creditos - $data['valor'] >= 0) {
                if ($data['valor'] > ($creditosInseridos + $creditosEstornados) ){

                
                    $this->getHelper('flashMessenger')
                         ->addMessage(array('error'=>"Não é possível utilizar o crédito em denários para estorno."));   
                    return;
                }

                if ($form->isValid($data)) {
                    
                    $data2 = $this->_request->getPost();
                    unset($data2['creditos']);
                    unset($data2['submit']);
                    unset($data2['documento']);
                    unset($data2['descricao']);
                    unset($data2['descricao2']);
                    unset($data2['saldo_futuro']);
                    unset($data2['saldo']);
                    $data2['valor'] = $data2['valor'];
                    $data2['id_cartao'] = $data2['id_cartao2'];
                    $data2['id_forma'] = 3;
                    unset($data2['id_cartao2']);
					
                    $data = $this->_request->getPost();
                    unset($data['creditos']);
                    unset($data['submit']);
                    unset($data['documento']);
                    unset($data['descricao']);
                    unset($data['descricao2']);
                    unset($data['saldo_futuro']);
                    unset($data['saldo']);
                    $data['id_estorno'] = 2;
                    $data['id_forma'] = 7;
                    $data['valor'] = $data['valor'] * -1;
                    $data['id_cartao'] = $rcartao->id_cartao;
                    unset($data['id_cartao2']);
                    
                    $auth = Zend_Auth::getInstance();
                    $login = $auth->getIdentity();
                    
                    
					$caixas = new Application_Model_Caixas();
					$caixa = $caixas->fetchrow("ip = '".$this->getRequest()->getServer('REMOTE_ADDR')."'");
					$data['id_caixa']=$caixa->id_caixa;
					if (is_numeric($caixa->id_caixa)){
						$data['id_caixa']=$caixa->id_caixa;
						
						$data['id_vendedor'] = $login->id_vendedor;
                    	
						$data2['id_caixa']=$caixa->id_caixa;
						$data2['id_vendedor'] = $login->id_vendedor;
						
                    	$entrada = new Application_Model_Creditos();
                    	$entrada->insert($data);
                    	$entrada2 = new Application_Model_Creditos();
                    	$entrada2->insert($data2);
                        $this->getHelper('flashMessenger')
                             ->addMessage(array('success'=>"Transferência realizada com sucesso."));   
                    	$this->_redirect("/cartao?busca={$data['id_cartao']}");
						}
					else
						{
						$this->getHelper('flashMessenger')
                             ->addMessage(array('error'=>"Caixa não cadastrado (".$this->getRequest()->getServer('REMOTE_ADDR').")!"));  
                    	$this->_redirect("/cartao"); 
						}	
                }
            }
            else{
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não foi possível extornar esse valor pois ele ultrapassa o valor de créditos existentes no cartão."));   
            }
        }        
    }
    public function substituirAction (){
        
        $cartao = new Application_Model_Cartao();
        $creditos = new Application_Model_Creditos();
        $saldo = $creditos->getCreditosPorTipo($this->_request->getParam('id_cartao'));
        
        if (count($saldo) == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não há crédito neste cartão."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        $rcartao = $cartao->find($this->_request->getParam('id_cartao'))->current();
        
        $form = new MinhaBiblioteca_Forms_Substituir();
        $form->populate($rcartao->toArray());
        $this->view->form = $form;
        $creditosInseridos = 0;
        $creditosEstornados = 0;
        foreach ($saldo as $formaPagamento => $valor) {
            if ($formaPagamento != 5 && $valor > 0){
                $creditosInseridos = $creditosInseridos + $valor;
            }
            if ($valor < 0){
                $creditosEstornados = $creditosEstornados + $valor;
            }
        }

        if ($creditosInseridos == 0){
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não é possível estornar este cartão, pois foi carregado exclusivamente com denários."));   
            $this->_redirect("/cartao?busca=" . $this->_request->getParam('id_cartao'));
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($rcartao->creditos - $data['valor'] >= 0) {
                if ($data['valor'] > ($creditosInseridos + $creditosEstornados) ){

                
                    $this->getHelper('flashMessenger')
                         ->addMessage(array('error'=>"Não é possível utilizar o crédito em denários para estorno."));   
                    return;
                }

                if ($form->isValid($data)) {
					
                    $data = $this->_request->getPost();
                    $data['id_cartao'] = $rcartao->id_cartao;
                    $data['id_estorno'] = 2;
                    $data['id_forma'] = 7;
                    $data['valor'] = $rcartao->creditos * -1;
                    unset($data['creditos']);
                    unset($data['id_cartao2']);
                    unset($data['submit']);
                    unset($data['descricao']);
                    unset($data['documento']);
					
                    $data2 = $this->_request->getPost();
                    $data2['valor'] = $rcartao->creditos;
                    $data2['id_cartao'] = $data2['id_cartao2'];
                    $data2['id_forma'] = 3;
                    unset($data2['creditos']);
                    unset($data2['id_cartao2']);
                    unset($data2['submit']);
                    unset($data2['descricao']);
                    unset($data2['documento']);
                    
                    $data3 = $this->_request->getPost();
                    
                    $auth = Zend_Auth::getInstance();
                    $login = $auth->getIdentity();                    
                    
					$caixas = new Application_Model_Caixas();
					$caixa = $caixas->fetchrow("ip = '".$this->getRequest()->getServer('REMOTE_ADDR')."'");
					$data['id_caixa']=$caixa->id_caixa;
					if (is_numeric($caixa->id_caixa)){
						$data['id_caixa']=$caixa->id_caixa;						
						$data['id_vendedor'] = $login->id_vendedor;
                    	
						$data2['id_caixa']=$caixa->id_caixa;
						$data2['id_vendedor'] = $login->id_vendedor;
						
						
						$cartao = new Application_Model_Cartao();
						$cartao->update(array('status'   => 1,
											  'documento'=>$rcartao->documento,
											  'descricao'=>$rcartao->descricao), 'id_cartao='.(int) $data2['id_cartao']);
						
						$cartao2 = new Application_Model_Cartao();
						$cartao2->update(array('status'=> 2,
											  'documento'=>$rcartao->documento), 'id_cartao='.(int) $data['id_cartao']);
						
                    	$entrada = new Application_Model_Creditos();
                    	$entrada->insert($data);
						
                    	$entrada2 = new Application_Model_Creditos();
                    	$entrada2->insert($data2);
						
                        $this->getHelper('flashMessenger')
                             ->addMessage(array('success'=>"Substituição realizada com sucesso."));   
                    	$this->_redirect("/cartao?busca={$rcartao->descricao}");
						}
					else
						{
						$this->getHelper('flashMessenger')
                             ->addMessage(array('error'=>"Caixa não cadastrado (".$this->getRequest()->getServer('REMOTE_ADDR').")!"));  
                    	$this->_redirect("/cartao"); 
						}	
                }
            }
            else{
            $this->getHelper('flashMessenger')
                 ->addMessage(array('error'=>"Não foi possível extornar esse valor pois ele ultrapassa o valor de créditos existentes no cartão."));   
            }
        }
        
    }
    
public function barAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		
		$barcodeOptions = array ('text' => $this->_request->getParam('id'), 'barHeight' => 40);
				// No required options
				$rendererOptions = array();
				$img=Zend_Barcode::render('code39', 'image', $barcodeOptions, $rendererOptions);
				
				
				
				echo $img ;  
	}
}

