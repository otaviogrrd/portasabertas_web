<?php

class MinhaBiblioteca_Forms_Denario extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $nome = $this->createElement('textarea', 'cartoes', array('label' => 'Cartões:', 'class' => 'input-m', 'autofocus'=>'autofocus', 'tabindex'=>1, 'cols' => '15', 'rows'=>10, 'type'=>'number'))->setRequired(true);
        $this->addElement($nome);

        $action = $this->createElement('text', 'valor', array('label' => 'Valor:', 'class' => 'input-m', 'tabindex'=>2));
        $this->addElement($action);

        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'tabindex'=>3));
        $this->addElement($submit);
        
        	
    }

}