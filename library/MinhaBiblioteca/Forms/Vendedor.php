<?php

class MinhaBiblioteca_Forms_Vendedor extends Zend_Form {

	public function init() {
		$this->setMethod('post');

		$login = $this->createElement('text', 'login', array('label' => 'Login do usuário', 'class' => 'input-m', 'autofocus'=>'autofocus', 'tabindex'=>1))->setRequired(true);
		$this->addElement($login);

		$senha = $this->createElement('password', 'senha', array('label' => 'Senha', 'class' => 'input-m', 'tabindex'=>2))->setRequired(true);
		$this->addElement($senha);
		$nivel  = $this->createElement('select', 'nivel', array('id' => 'nivel','label' => '* Perfil do usuário','class' => 'input-m', 'tabindex'=>3))->setRequired(true);
		$auth = Zend_Auth::getInstance ();
		$login = $auth->getIdentity();
		if ($login->nivel ==1){
			$Options[1] = 'Administrador';
			}
		$Options[2] = 'Caixa';
		$Options[3] = 'Barraca';
		$Options[4] = 'Fiscal de Barraca';
		$nivel ->addMultiOptions($Options);
		$this->addElement($nivel);
        $obj_barracas = new Application_Model_Barracas();
        $id_barraca  = $this->createElement('multiselect', 'id_barraca', array('id' => 'barraca','label' => '* Barracas ','class' => 'input-m'))->setRequired(false);
        $barracas = $obj_barracas->fetchPair();

		$id_barraca ->addMultiOptions($barracas);
		$this->addElement($id_barraca);
		$submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp', 'tabindex'=>4));
        $this->addElement($submit);

	}

}