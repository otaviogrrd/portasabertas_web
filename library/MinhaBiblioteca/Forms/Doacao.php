<?php

class MinhaBiblioteca_Forms_Doacao extends Zend_Form {

    public function init() {
     $this->setMethod('post');

        
        $valor = $this->createElement('text', 'valor', array('label' => 'Valor a ser doado:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($valor);
        
		$forma  = $this->createElement('select', 'id_forma ', array('label' => 'Forma de pagamento','class' => 'input-m'));
		$Options=array(	3 => 'Dinheiro',
						2 => 'Cartão de débito',
						1 => 'Cartão de crédito',
						4 => 'Cheque');		
		$forma ->addMultiOptions($Options);
		$this->addElement($forma );
		
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'onclick'=>"return confirma_doacao()"));
        $this->addElement($submit);
        
      
    }

}