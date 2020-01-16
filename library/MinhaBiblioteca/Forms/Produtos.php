<?php

class MinhaBiblioteca_Forms_Produtos extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $descricao = $this->createElement('text', 'descricao', array('label' => 'Descrição:', 'class' => 'input-m', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $this->addElement($descricao);
        
        $valor = $this->createElement('text', 'valor', array('label' => 'Valor de venda:', 'class' => 'input-m', 'tabindex'=>2))->setRequired(true);
        $this->addElement($valor);

        $custo = $this->createElement('text', 'custo', array('label' => 'Preço de custo:', 'class' => 'input-m', 'tabindex'=>3))->setRequired(true);
        $this->addElement($custo);
        
        $estoque = $this->createElement('text', 'estoque', array('label' => 'Estoque:', 'class' => 'input-m', 'tabindex'=>4))->setRequired(true);
        $this->addElement($estoque);
        
        $obj_barracas = new Application_Model_Barracas();
        $barraca  = $this->createElement('select', 'barraca', array('id' => 'barraca','label' => '* Barracas ','class' => 'input-m', 'tabindex'=>5))->setRequired(true);

		$barraca ->addMultiOptions($obj_barracas->fetchPair());
		$this->addElement($barraca);
		
        $ocultar = $this->createElement('checkbox', 'ocultar', array('label' => 'Ocultar?', 'class' => 'input-m', 'tabindex'=>6));
        $this->addElement($ocultar);
        
        $ordem = $this->createElement('text', 'ordem', array('label' => 'Ordem de exibição na lista', 'class' => 'input-m', 'tabindex'=>7));
        $this->addElement($ordem);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'tabindex'=>8));
        $this->addElement($submit);
        
      
        
    }

}