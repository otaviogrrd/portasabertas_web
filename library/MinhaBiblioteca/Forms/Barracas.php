<?php

class MinhaBiblioteca_Forms_Barracas extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $nome = $this->createElement('text', 'nome', array('label' => 'Nome:', 'class' => 'input-m', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $this->addElement($nome);

        $action = $this->createElement('checkbox', 'aceita_pagamento', array('label' => 'Aceita pagamento?', 'class' => 'input-m', 'tabindex'=>2));
        $this->addElement($action);
		
        $action2 = $this->createElement('checkbox', 'aceita_negativo', array('label' => 'Aceita Negativo?', 'class' => 'input-m', 'tabindex'=>3));
        $this->addElement($action2);

        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'tabindex'=>4));
        $this->addElement($submit);
        
        	
    }

}