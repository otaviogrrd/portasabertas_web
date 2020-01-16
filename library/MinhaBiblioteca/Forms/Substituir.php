<?php

class MinhaBiblioteca_Forms_Substituir extends Zend_Form {

    public function init() {
     $this->setMethod('post');

        $id_cartao = $this->createElement('text', 'id_cartao', array('label' => 'Cartão Origem:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($id_cartao);		
        
        $descricao = $this->createElement('text', 'descricao', array('label' => 'Portador:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($descricao);
		
        $documento = $this->createElement('text', 'documento', array('label' => '* Conferir Documento:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($documento);
		
        $creditos = $this->createElement('text', 'creditos', array('label' => 'Créditos:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($creditos);        
        
        $id_cartao2 = $this->createElement('text', 'id_cartao2', array('label' => 'Cartão Destino:', 'class' => 'input-m','autocomplete' => 'off', 'tabindex'=>1));
        $this->addElement($id_cartao2);
				
        $submit = $this->createElement('submit', 'submit', array('label' => 'Confirmar', 'class' => 'input-pp', 'tabindex'=>2));
        $this->addElement($submit);
        
      
    }

}