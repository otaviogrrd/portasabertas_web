<?php

class MinhaBiblioteca_Forms_Cartao extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $numero = $this->createElement('text', 'id_cartao', array('label' => 'Número:', 'class' => 'input-m', 'autocomplete' => 'off', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $descricao = $this->createElement('text', 'descricao', array('label' => '* Portador com pelo menos um sobrenome:', 'class' => 'input-m','autocomplete'=>'off', 'tabindex'=>2))->setRequired(true);
        $documento = $this->createElement('text', 'documento', array('label' => 'Documento em caso de extravio:', 'class' => 'input-m','autocomplete'=>'off', 'tabindex'=>3));
        $situacao = $this->createElement('text', 'situacao', array('label' => 'Situação:', 'class' => 'input-m','autocomplete'=>'off','readonly'=>'readonly'));
        
        $this->addElement($numero);
        $this->addElement($descricao);
        $this->addElement($documento);
        $this->addElement($situacao);
        
                
        $submit = $this->createElement('submit', 'submit', array('label' => 'Associar', 'class' => 'input-pp' ,'disabled' => true, 'tabindex'=>4));
        $this->addElement($submit);
        
        	
    }

}