<?php

class MinhaBiblioteca_Forms_Preparar extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $file = new Zend_Form_Element_File('cartoes');
        $file->setLabel('Arquivo contendo os cartões (um número por linha, arquivo .txt):')
             ->addValidator('Count', false, 1)
             ->addValidator('Extension', false, 'txt');
        $this->setAttrib('enctype', 'multipart/form-data');

        $this->addElement($file);
        $submit = $this->createElement('submit', 'submit', array('label' => 'Limpar e preparar ambiente', 'class' => 'input-pp'));
        $this->addElement($submit);
        
        	
    }

}