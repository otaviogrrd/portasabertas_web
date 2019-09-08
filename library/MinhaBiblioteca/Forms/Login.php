<?php

class MinhaBiblioteca_Forms_Login extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $nome = $this->createElement('text', 'nome', array('label' => 'Nome:', 'class' => 'input-m', 'autocomplete'=> 'off', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $this->addElement($nome);
        
        
        $senha = $this->createElement('password', 'senha', array('label' => 'Senha:', 'class' => 'input-m', 'tabindex'=>2))->setRequired(true);
        $this->addElement($senha);

        $submit = $this->createElement('submit', 'submit', array('label' => 'Entrar', 'class' => 'input-pp', 'tabindex'=>3));
        $this->addElement($submit);
    }
}