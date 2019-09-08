<?php

class MinhaBiblioteca_Forms_Tarefa extends Zend_Form {

    public function init() {
        $this->setMethod('post');

        $nome_tarefa = $this->createElement('text', 'nome_tarefa', array('label' => 'Nome Tarefa:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($nome_tarefa);
        
        $descricao_tarefa = $this->createElement('text', 'descricao_tarefa', array('label' => 'Descrição Tarefa:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($descricao_tarefa);
      
        $dt_inicio_tarefa = $this->createElement('text', 'dt_inicio_tarefa', array('label' => 'Data de inicio da Tarefa:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($dt_inicio_tarefa);
        	
        
        $dt_previsao_tarefa = $this->createElement('text', 'dt_previsao_tarefa', array('label' => 'Data de previsão da tarefa:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($dt_previsao_tarefa);
        
  		$prioridade_tarefa  = $this->createElement('select', 'prioridade_tarefa ', array('label' => 'Prioridade da tarefa ','class' => 'input-m'));
		$Options[0] = 'Baixa';
		$Options[1] = 'Média';
		$Options[2] = 'Alta';
		
		$prioridade_tarefa ->addMultiOptions($Options);
		$this->addElement($prioridade_tarefa );
		
		$obs_tarefa = $this->createElement('textArea', 'obs_tarefa', array('label' => 'Observação', 'class' => 'input-m', 'cols' => '50', 'rows' => '10'))->setRequired(false);
        $this->addElement($obs_tarefa);
        
          
        $percentual_tarefa = $this->createElement('text', 'percentual_tarefa', array('label' => 'Percentual de conclusão da tarefa:', 'class' => 'input-m'))->setRequired(true);
        $this->addElement($percentual_tarefa);	
		
        
          
  		$status_tarefa  = $this->createElement('select', 'status_tarefa ', array('label' => 'Prioridade da tarefa ','style' => 'width: 150px;height: 25px;'));
		$Options[0] = 'Criado';
		$Options[1] = 'Andamento';
		$Options[2] = 'Cancelado';
		$Options[3] = 'Concluído';
		
		$status_tarefa ->addMultiOptions($Options);
		$this->addElement($status_tarefa );
        
		
		$objFuncionario = new Application_Model_DbTable_Funcionario();
		$id_funcionario = $this->createElement('select', 'id_funcionario', array('label' => '* Responsável:', 'class' => 'combo',
		 'style' => 'width: 250px;height: 25px;'))->setRequired(true);
		
		$vazio[0] = 'Selecione algum responsável';
		$id_funcionario->addMultiOptions($vazio);
		$id_funcionario->addMultiOptions($objFuncionario->fetchPair());
        $this->addElement($id_funcionario);
        
        
    
        $submit = $this->createElement('submit', 'submit', array('label' => 'Salvar', 'class' => 'input-pp'));
        $this->addElement($submit);

        
    }

}
?>