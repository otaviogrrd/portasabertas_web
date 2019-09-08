<?php

class MinhaBiblioteca_Forms_Equipamentos extends Zend_Form {

    public function init() {
        $this->setMethod('post');
        $obj_barracas = new Application_Model_Barracas();
        $id_barraca  = $this->createElement('select', 'id_barraca', array('id' => 'barraca','label' => '* Barracas ','class' => 'input-m'))->setRequired(true);

		$id_barraca ->addMultiOptions($obj_barracas->fetchPair());
		$this->addElement($id_barraca);
		$descricao = $this->createElement('text', 'descricao', array('label' => 'Descricao:', 'class' => 'input-m'))->setRequired(false);
        $this->addElement($descricao);
        $responsavel = $this->createElement('text', 'responsavel', array('label' => 'Responsavel:', 'class' => 'input-m'))->setRequired(false);
        $this->addElement($responsavel);
        $ip = $this->createElement('text', 'ip', array('label' => 'Endereco IP:', 'class' => 'input-m'))->setRequired(false);
        $this->addElement($ip);
        $mac = $this->createElement('text', 'mac', array('label' => 'Mac Address(opcional):', 'class' => 'input-m'))->setRequired(false);
        $this->addElement($mac);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp'));
        $this->addElement($submit);
        
        	
    }

}