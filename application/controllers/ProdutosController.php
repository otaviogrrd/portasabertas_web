<?php
class ProdutosController extends Zend_Controller_Action
{
public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ())
			$this->_redirect ( '/' );
	
	}
    public function indexAction ()
    {
        
        $produtos = new Application_Model_Produtos();
        $produtosArray = $produtos->relatorioProdutos();
        $paginator = Zend_Paginator::factory($produtosArray);
        $paginator->setItemCountPerPage(300);
        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
        $this->view->paginator = $paginator;
    }
    
    
public function meusprodutosAction ()
    {
    	$id_barracas = $this->_request->getParam('id_barracas');
    	if(preg_match("/\d+/",$id_barracas)){
	        
	        $produtos = new Application_Model_Produtos();
	        $produtosArray = $produtos->select()
	        						  ->where("ocultar = 0 and barraca = ?", $id_barracas)
	        						  ->order("ordem", $id_barracas)
	        						  ->query()
	        						  ->fetchAll();
	        $paginator = Zend_Paginator::factory($produtosArray);
	        $paginator->setItemCountPerPage(50);
	        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
	        $this->view->paginator = $paginator;
	        $this->view->barraca = $id_barracas;
	        $barracas = new Application_Model_Barracas();
	        $barraca = $barracas->select()
	        				    ->where("id_barraca = ?", $id_barracas)
	        				    ->query()
	        				    ->fetchObject();
	        $this->view->nome = $barraca->nome;
    		}
    }

public function meusProdutosPagamentoAction ()
    {
    	#fixo nesta versao, evoluir para o tipo da barraca na proxima.
    	$id_barracas = $this->_request->getParam('id_barracas');
    	if(preg_match("/\d+/",$id_barracas)){
	        
	        $produtos = new Application_Model_Produtos();
	        $produtosArray = $produtos->select()
	        						  ->where("barraca = ?", $id_barracas)
	        						  ->query()
	        						  ->fetchAll();
	        $paginator = Zend_Paginator::factory($produtosArray);
	        $paginator->setItemCountPerPage(50);
	        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
	        $this->view->paginator = $paginator;
	        $this->view->barraca = $id_barracas;
	        $barracas = new Application_Model_Barracas();
	        $barraca = $barracas->select()
	        				    ->where("id_barraca = ?", $id_barracas)
	        				    ->query()
	        				    ->fetchObject();
	        $this->view->nome = $barraca->nome;
	        
    		}
    }	
public function meusProdutosNegativoAction ()
    {
    	#fixo nesta versao, evoluir para o tipo da barraca na proxima.
    	$id_barracas = $this->_request->getParam('id_barracas');
    	if(preg_match("/\d+/",$id_barracas)){
	        
	        $produtos = new Application_Model_Produtos();
	        $produtosArray = $produtos->select()
	        						  ->where("barraca = ?", $id_barracas)
	        						  ->query()
	        						  ->fetchAll();
	        $paginator = Zend_Paginator::factory($produtosArray);
	        $paginator->setItemCountPerPage(50);
	        $paginator->setCurrentPageNumber($this->_request->getParam('pagina'));
	        $this->view->paginator = $paginator;
	        $this->view->barraca = $id_barracas;
	        $barracas = new Application_Model_Barracas();
	        $barraca = $barracas->select()
	        				    ->where("id_barraca = ?", $id_barracas)
	        				    ->query()
	        				    ->fetchObject();
	        $this->view->nome = $barraca->nome;
	        
    		}
    }
    public function newAction ()
    {
        require_once 'MinhaBiblioteca/Forms/Produtos.php';
        $form = new MinhaBiblioteca_Forms_Produtos();
        $this->view->form = $form;
       
        $produtos = new Application_Model_Produtos();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($form->isValid($data)) {
            	$data["valor"] = str_replace(',','.',$data["valor"]);
                unset($data['submit']);
                $produtos->insert($data);
                $this->getHelper('flashMessenger')
                     ->addMessage(array('success'=>"Produto inserido com sucesso."));	
                $this->_redirect('/Produtos');
            }
        }
    }
    
	public function editAction() {
		
        $produtos = new Application_Model_Produtos();
        
		$rprodutos = $produtos->find($this->_request->getParam('id_produtos'))->current();
     	
		require_once 'MinhaBiblioteca/Forms/Produtos.php';
        $form = new MinhaBiblioteca_Forms_Produtos();
        
		$form->populate($rprodutos->toArray());
		if ($this->_request->isPost()) {
			$data = $this->_request->getPost();
			if ($form->isValid($data)) 
			{
				$data["valor"] = str_replace(',','.',$data["valor"]);
				unset($data['submit']);
				//$data['dt_modifica_etapa']= date('Y-m-d h:i:s');
				$produtos->update($data, 'id_produtos=' . (int) $this->_getParam('id_produtos'));
				$this->getHelper('flashMessenger')
                     ->addMessage(array('success'=>"Produto atualizado com sucesso."));	
					$this->_redirect('/Produtos');
			}
		}
		$this->view->form = $form;
	}
    

public function deleteAction() {
		$id_produtos = $this->_request->getParam('id_produtos');
		
        $produtos = new Application_Model_Produtos();
		$rprodutos = $produtos->find($this->_request->getParam('id_produtos'))->current();
		// Colocar verificação de relacionamento antes de excluir
		$rprodutos->delete();
		$this->getHelper('flashMessenger')
             ->addMessage(array('success'=>"Produto apagado com sucesso."));	
		$this->_redirect('/Produtos');		
	}
	
	public function barAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		
		$barcodeOptions = array ('text' => $this->_request->getParam('id'), 'barHeight' => 30);
				// No required options
				$rendererOptions = array();
				$img=Zend_Barcode::render('ean13', 'image', $barcodeOptions, $rendererOptions);
				echo $img ;  
	}
	
	public function pdfAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		Zend_Pdf_Page::SIZE_A4;
		$pdf = new Zend_Pdf(); 
		//... your work 
		
		//a font is mandatory for Pdf 
		Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf'); 

		for ($x=30;$x<= (855);$x=$x+75){
			
			$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 8,'factor' => 3); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 30); 
			if ($x==30)
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdf)->draw(); 
			else
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
				
			$barcodeOptions = array('text' => rand(10000000,99999999),'barHeight' => 32,'fontSize'=> 8,'factor' => 3); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 220); 
			$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
							  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw(); 

			$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 8,'factor' => 3); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 420); 
			$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
							  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
			}		
		 
		// and the end render your Zend_Pdf 
		$pdfWithBarcode->save('testBarcode.pdf');
		$this->_redirect('/testBarcode.pdf');
			
		}	
		public function pdf2Action() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		$pdf = new Zend_Pdf(); 
		//... your work 
		Zend_Pdf_Page::SIZE_A4;
		//a font is mandatory for Pdf 
		Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf'); 

		for ($x=17;$x<= 853;$x=$x+76){
			
			$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 5,'factor' => 3.5); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 25); 
			if ($x==17)
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdf)->draw(); 
			else
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
				
			$barcodeOptions = array('text' => rand(10000000,99999999),'barHeight' => 32,'fontSize'=> 5,'factor' => 3.5); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 225); 
			$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
							  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw(); 

			$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 5,'factor' => 3.5); 
			$rendererOptions = array('topOffset' => $x,'leftOffset'=> 423); 
			$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
							  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
			}		
		 
		// and the end render your Zend_Pdf 
		$pdfWithBarcode->save('testBarcode.pdf');
		$this->_redirect('/testBarcode.pdf');
			
		}	
	public function pdfpageAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		$pdf = new Zend_Pdf(); 
		$pdf->properties['Title'] = "Cartoes portas abertas";
		$pdf->properties['Author'] = "Marcello Coutinho";
		for ($page=0;$page<=2;$page++){
			$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
			Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf');
			for ($x=25;$x<= 800;$x=$x+72){
	        	$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 6,'factor' => 3.3); 
				$rendererOptions = array('topOffset' => $x,'leftOffset'=> 22); 
	        	$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf',$barcodeOptions, $rendererOptions)->setResource($pdf,$page)->draw();
	        	
	        	$barcodeOptions = array('text' => rand(10000000,99999999),'barHeight' => 32,'fontSize'=> 6,'factor' => 3.3); 
				$rendererOptions = array('topOffset' => $x,'leftOffset'=> 210); 
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', $barcodeOptions, $rendererOptions)->setResource($pdf,$page)->draw(); 
	
				$barcodeOptions = array('text' => rand(10000000,99999999), 'barHeight' => 32,'fontSize'=> 6,'factor' => 3.3); 
				$rendererOptions = array('topOffset' => $x,'leftOffset'=> 400); 
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf',$barcodeOptions, $rendererOptions)->setResource($pdf,$page)->draw(); 
	        	}
			}

		//gera o arquivo
        //$pdfWithBarcode->render();
		$pdfFilename = 'teste1.pdf';

		$pdfWithBarcode->save('teste1.pdf');

		header('Content-disposition: attachment; filename=' . $pdfFilename);
		header('Content-type: application/pdf');
		
		readfile($pdfFilename);
		}
		
	public function pdfpagecartoesAction() {
		
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$pdf = new Zend_Pdf(); 
		$pdf->properties['Title'] = "Cartoes portas abertas";
		$pdf->properties['Author'] = "Marcello Coutinho";
		#primeira pagina
		$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
		Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf');

		
		$cartao = new Application_Model_Cartao();
        $cartaoArray = $cartao->fetchAll();
        #$codigos_por_arquivo=990;
        $codigos=1;
        $codigos_por_arquivo=1;
        $arquivos=1;
        $pagina=0;
        $leftOffset=22;
		$topOffset=25;
		foreach ($cartaoArray as $cb){
			if ($codigos_por_arquivo==991){
				$pdfWithBarcode->save("codigos_PA3_{$arquivos}.pdf");
				unset($pdfWithBarcode);
				unset ($pdf);
				$pdf = new Zend_Pdf(); 
				$pdf->properties['Title'] = "Cartoes portas abertas";
				$pdf->properties['Author'] = "Marcello Coutinho";
				$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
				Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf');
				$arquivos++;
				$pagina=0;
				$codigos=1;
				$codigos_por_arquivo=1;
				$leftOffset=22;
				$topOffset=25;
				}
			if ($codigos==34){
				$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
				Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf');
				// and the end render your Zend_Pdf 
				$codigos=1;
				$pagina++;
				$leftOffset=22;
				$topOffset=25;
				}
			$barcodeOptions = array('text' => $cb->id_cartao, 'barHeight' => 32,'fontSize'=> 6,'factor' => 3.3);
			$rendererOptions = array('topOffset' => $topOffset,'leftOffset'=> $leftOffset); 
			$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf',$barcodeOptions, $rendererOptions)->setResource($pdf,$pagina)->draw();
			switch($leftOffset){
				case 22:
					$leftOffset=210;
					break;
				case 210:
					$leftOffset=400;
					break;
				case 400:
					$leftOffset=22;
					$topOffset=$topOffset+72;
					}
			$codigos++;
			$codigos_por_arquivo++;
			}
		}
			
	public function pdf3Action() {
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		$cartao = new Application_Model_Cartao();
        $cartaoArray = $cartao->fetchAll();
        print "<PRE>Gerando codigos...";
        $pagina=1;
        $codigos=1;
        foreach ($cartaoArray as $cb){
        	switch ($codigos){
        		case 35:
					// and the end render your Zend_Pdf 
					$pdfWithBarcode->save("codigos_PA3_{$pagina}.pdf");
					print "pagina{$pagina}..ok<br>";
					//$this->_redirect('/codigos_portas_abertas.pdf');
					$codigos=1;
					$pagina++;
				case 1:
        			Zend_Pdf_Page::SIZE_A4;
					$pdf = new Zend_Pdf();
					//a font is mandatory for Pdf 
					Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf'); 
					$leftOffset=30;
					$topOffset=30;
					break;
				default:
					$barcodeOptions = array('text' => $cb->id_cartao, 'barHeight' => 32,'fontSize'=> 8,'factor' => 3); 
					$rendererOptions = array('topOffset' => $topOffset,'leftOffset'=> $leftOffset); 
					if ($leftOffset==30)
						$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
										  $barcodeOptions, $rendererOptions)->setResource($pdf)->draw(); 
					else
						$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
										  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
					switch($leftOffset){
						case 30:
							$leftOffset=220;
							break;
						case 220:
							$leftOffset=420;
							break;
						case 420:
							$leftOffset=30;
							$topOffset=$topOffset+75;
					}
					
        		}
        	$codigos++;
        	}
		}
		
	public function pdf4Action() {
		// Otávio 2020-01-15 adicionado este método para imprimir os códigos dos cartões comanda
		// para fazer isso basta temporariamente substituir o método de consulta de preços(logo abaixo)
		// o resultado será diversos pdf gerados na pasta public
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
		$cartao = new Application_Model_Cartao();
        $cartaoArray = $cartao->select()
	        				  ->where("tp_cartao = 1")
							  ->order('descricao')
	        				  ->query()
	        				  ->fetchAll();
        $pagina=1;
        $codigos=1;
        foreach ($cartaoArray as $cb => $value){			
        	switch ($codigos){
        		case 34:
					// and the end render your Zend_Pdf 
					$pdfWithBarcode->save("codigos_PA3_{$pagina}.pdf");
					print "<a href='/codigos_PA3_{$pagina}.pdf'> pagina{$pagina}..ok</a><br>";
					$codigos=1;
					$pagina++;
				case 1:
        			Zend_Pdf_Page::SIZE_A4;
					$pdf = new Zend_Pdf();
					//a font is mandatory for Pdf 
					Zend_Barcode::setBarcodeFont(dirname(__FILE__) . '/Vera.ttf'); 
					$leftOffset=30;
					$topOffset=30;
			}
			// desenha os codigos
			$barcodeOptions = array('text' =>  $value['id_cartao'] , 'barHeight' => 32,'fontSize'=> 8,'factor' => 3); 
			$rendererOptions = array('topOffset' => $topOffset,'leftOffset'=> $leftOffset); 
			if ($leftOffset==30)
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdf)->draw(); 
			else
				$pdfWithBarcode = Zend_Barcode::factory('ean13', 'pdf', 
								  $barcodeOptions, $rendererOptions)->setResource($pdfWithBarcode)->draw();
			switch($leftOffset){
				case 30:
					$leftOffset=220;
					break;
				case 220:
					$leftOffset=420;
					break;
				case 420:
					$leftOffset=30;
					$topOffset=$topOffset+75;
			}
        	$codigos++;
		}
		// and the end render your Zend_Pdf 
		if ($codigos != 34){
			$pdfWithBarcode->save("codigos_PA3_{$pagina}.pdf");
			print "<a href='/codigos_PA3_{$pagina}.pdf'> pagina{$pagina}..ok</a><br>"; 
		}
		
	}
	public function precosAction(){
		$model = new Application_Model_Produtos();
		$this->view->produtos = $model->getPrecos();				
	}
}

