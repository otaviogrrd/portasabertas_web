<?php

class MinhaBiblioteca_Forms_Projeto extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $descricao_projeto = $this->createElement('text', 'descricao_projeto', array('label' => 'Descrição projeto:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($descricao_projeto);
        
        $dt_inicio_projeto = $this->createElement('text', 'dt_inicio_projeto', array('label' => 'Data de inicio do projeto:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($dt_inicio_projeto);

        $dt_previsao_projeto = $this->createElement('text', 'dt_previsao_projeto', array('label' => 'Data de Previsão do projeto:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($dt_previsao_projeto);
        
        $objClientes = new Application_Model_DbTable_Cliente();
		$id_cliente = $this->createElement('select', 'id_cliente', array('label' => '* Clientes', 'class' => 'input-m',
		 'style' => 'width: 150px;height: 25px;'))->setRequired(true);
		$id_cliente->addMultiOptions($objClientes->fetchPair());
        $this->addElement($id_cliente);
        
        $dominio_projeto = $this->createElement('text', 'dominio_projeto', array('label' => 'Domínio do projeto', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($dominio_projeto);
        
        $obs_projeto = $this->createElement('textArea', 'obs_projeto', array('label' => 'Observação', 'class' => 'input-m', 'cols' => '50', 'rows' => '10'))->setRequired(false);
        $this->addElement($obs_projeto);
        
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp'));
        $this->addElement($submit);

        
    }

}
?>