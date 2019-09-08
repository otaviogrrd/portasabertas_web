<?php

class MinhaBiblioteca_Forms_Credito extends Zend_Form {

    public function init() {
     $this->setMethod('post');

        $id_cartao = $this->createElement('text', 'id_cartao', array('label' => 'Cartão:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($id_cartao);
        $descricao = $this->createElement('text', 'descricao', array('label' => 'Portador:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($descricao);
        $situacao = $this->createElement('text', 'situacao', array('label' => 'Situação:', 'class' => 'input-m','autocomplete'=>'off','disable'=> 'disable'));
        $this->addElement($situacao);
        $creditos = $this->createElement('text', 'creditos', array('label' => 'Saldo Atual:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($creditos);
        
        $valor = $this->createElement('text', 'valor', array('label' => 'Valor a ser adicionado:', 'class' => 'input-m', 'autocomplete' => 'off', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $this->addElement($valor);
        $saldo_futuro = $this->createElement('text', 'saldo_futuro', array('label' => 'Saldo após o crédito:', 'class' => 'input-m','disabled'=>'disabled'));
        $this->addElement($saldo_futuro);
          
		$forma  = $this->createElement('select', 'id_forma ', array('label' => 'Forma de pagamento','class' => 'input-m', 'tabindex'=>2));
		$Options=array(	''=>'Escolha',
                        3 => 'Dinheiro',
						2 => 'Cartão de débito',
						1 => 'Cartão de crédito',
						4 => 'Cheque');
		$forma ->addMultiOptions($Options);
        $forma->setRequired(TRUE);
		$this->addElement($forma );
		
        
        $submit = $this->createElement('submit', 'confirmar', array('label' => 'Confirmar', 'class' => 'input-pp','disabled'=>true, 'tabindex'=>3));
        $this->addElement($submit);
        
      
    }

}