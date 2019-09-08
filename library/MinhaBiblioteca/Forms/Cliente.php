<?php

class MinhaBiblioteca_Forms_Cliente extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $nomerazao = $this->createElement('text', 'nome_razao_cliente', array('label' => 'Nome-Razão:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($nomerazao);

        $cpfcnpj = $this->createElement('text', 'cpf_cnpj_cliente', array('label' => 'CPF/CNPJ:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($cpfcnpj);
        
        $endereco = $this->createElement('text', 'endereco_cliente', array('label' => 'Endereço:', 'class' => 'input-m'));
        $this->addElement($endereco);

        $cidade = $this->createElement('text', 'cidade_cliente', array('label' => 'Cidade:', 'class' => 'input-m'));
        $this->addElement($cidade);
        
        $uf = $this->createElement('text', 'uf_cliente', array('label' => 'UF:', 'class' => 'input-m'));
        $this->addElement($uf);
        
        $email = $this->createElement('text', 'email_cliente', array('label' => 'E-mail:', 'class' => 'input-m'));
        $this->addElement($email);
        
        $contato = $this->createElement('text', 'contato_cliente', array('label' => 'Contato:', 'class' => 'input-m'));
        $this->addElement($contato);
        
        $fone = $this->createElement('text', 'fone_cliente', array('label' => 'Telefone:', 'class' => 'input-m'));
        $this->addElement($fone);
        
        $ativo = $this->createElement('checkbox','status_cliente',array('label'=>'Ativo','checked'=>'checked','class' => 'input-m')); 
		$this->addElement($ativo);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp'));
        $this->addElement($submit);
    }

}