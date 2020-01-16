<?php

class MinhaBiblioteca_Forms_Cartao extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $numero = $this->createElement('text', 'id_cartao', array('label' => 'Número:', 'class' => 'input-m', 'autocomplete' => 'off', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $descricao = $this->createElement('text', 'descricao', array('label' => 'Nome:', 'class' => 'input-m','autocomplete'=>'off', 'tabindex'=>2))->setRequired(true);
        $sobrenome = $this->createElement('text', 'sobrenome', array('label' => 'Sobrenome:', 'class' => 'input-m','autocomplete'=>'off', 'tabindex'=>3))->setRequired(true);
        $documento = $this->createElement('text', 'documento', array('label' => 'Documento em caso de extravio:', 'class' => 'input-m','autocomplete'=>'off', 'tabindex'=>4));
        $situacao = $this->createElement('text', 'situacao', array('label' => 'Situação:', 'class' => 'input-m','autocomplete'=>'off','readonly'=>'readonly'));
        
        $this->addElement($numero);
        $this->addElement($descricao);
        $this->addElement($sobrenome);
        $this->addElement($documento);
        $this->addElement($situacao);
        
                
        $submit = $this->createElement('submit', 'submit', array('label' => 'Associar', 'class' => 'input-pp' ,'disabled' => true, 'tabindex'=>5));
        $this->addElement($submit);
        
        	
    }

}