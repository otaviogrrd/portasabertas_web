<?php

class MinhaBiblioteca_Forms_Caixas extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $descricao = $this->createElement('text', 'descricao', array('label' => 'Descrição:', 'class' => 'input-m', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
        $this->addElement($descricao);
        
        $localizacao = $this->createElement('text', 'localizacao', array('label' => 'Localização:', 'class' => 'input-m', 'tabindex'=>2))->setRequired(true);
        $this->addElement($localizacao);

        $ip = $this->createElement('text', 'ip', array('label' => 'Endereço IP:', 'class' => 'input-m', 'tabindex'=>3))->setRequired(true);
        $this->addElement($ip);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'tabindex'=>4));
        $this->addElement($submit);
        
      
        
    }

}