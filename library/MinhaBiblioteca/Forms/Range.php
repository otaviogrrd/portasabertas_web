<?php

class MinhaBiblioteca_Forms_Range extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $inicio = $this->createElement('text', 'prefixo', array('label' => 'Prefixo:', 'class' => 'input-m','disabled'=>'disabled'));
        $digitos = $this->createElement('text', 'digitos', array('label' => 'Digitos:', 'class' => 'input-m','disabled'=>'disabled','value'=> "8"));
        $qtd = $this->createElement('text', 'qtd', array('label' => 'Quantidade:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($inicio);
        $this->addElement($digitos);
        $this->addElement($qtd);
        
                
        $submit = $this->createElement('submit', 'submit', array('label' => 'Criar', 'class' => 'input-pp'));
        $this->addElement($submit);
        
        	
    }

}