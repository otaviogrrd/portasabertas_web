<?php

class MinhaBiblioteca_Forms_Mesclar extends Zend_Form {

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
		
        $descricao2 = $this->createElement('text', 'descricao2', array('label' => 'Portador:', 'class' => 'input-m','disable'=> 'disable'));
        $this->addElement($descricao2);
		
		$saldo = $this->createElement('text', 'saldo', array('label' => 'Saldo:', 'class' => 'input-m','disabled'=>'disabled'));
        $this->addElement($saldo);
		
        $valor = $this->createElement('text', 'valor', array('label' => 'Valor:', 'class' => 'input-m', 'autocomplete' => 'off', 'tabindex'=>2, 'autofocus'=>'autofocus'))->setRequired(true);
        $this->addElement($valor);
      	
		$saldo_futuro = $this->createElement('text', 'saldo_futuro', array('label' => 'Saldo após transferência:', 'class' => 'input-m','disabled'=>'disabled'));
        $this->addElement($saldo_futuro);
		
        $submit = $this->createElement('submit', 'submit', array('label' => 'Confirmar', 'class' => 'input-pp', 'tabindex'=>3));
        $this->addElement($submit);
        
      
    }

}