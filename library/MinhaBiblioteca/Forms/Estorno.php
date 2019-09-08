<?php

class MinhaBiblioteca_Forms_Estorno extends Zend_Form {

    public function init() {
     $this->setMethod('post');

        $id_cartao = $this->createElement('text', 'id_cartao', array('label' => 'Cartao:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($id_cartao);
        
        $id_cartao = $this->createElement('text', 'descricao', array('label' => 'Portador:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($id_cartao);
        $documento = $this->createElement('text', 'documento', array('label' => '* Conferir Documento:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($documento);
        $creditos = $this->createElement('text', 'creditos', array('label' => 'Creditos:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($creditos);
        $situacao = $this->createElement('text', 'situacao', array('label' => 'Situação:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($situacao);
        
        $valor = $this->createElement('text', 'valor', array('label' => 'Valor a ser extornado:', 'class' => 'input-m', 'autocomplete' => 'off', 'tabindex'=>1, 'autofocus'=>'autofocus'))->setRequired(true);
        $this->addElement($valor);
      	
        $obj_estornos = new Application_Model_Estornos();
        $id_estorno  = $this->createElement('select', 'id_estorno', array('id' => 'id_estorno','label' => '* Tipo do Estorno ','class' => 'input-m', 'tabindex'=>2))->setRequired(true);
        $list = array(''=>'Escolha');

		$id_estorno ->addMultiOptions(array_merge($list, $obj_estornos->fetchPair()));
		$this->addElement($id_estorno);
        
		$forma  = $this->createElement('select', 'id_forma ', array('label' => 'Forma de pagamento utilizada','class' => 'input-m', 'tabindex'=>3));
		$Options=array(	''=>'Escolha',
                        11 => 'Cartão de crédito',
						6 => 'Cartão de débito',
						7 => 'Dinheiro',
						9 => 'Cheque');		
		$forma ->addMultiOptions($Options);
		$this->addElement($forma );
		
		$saldo_futuro = $this->createElement('text', 'saldo_futuro', array('label' => 'Saldo após o estorno:', 'class' => 'input-m','disabled'=>'disabled'));
        $this->addElement($saldo_futuro);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Confirmar', 'class' => 'input-pp', 'tabindex'=>4));
        $this->addElement($submit);
        
      
    }

}