<?php
class JsonController extends Zend_Controller_Action
{
    public $db;
    public $path;
    public function init(){
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->path = APPLICATION_PATH.'/models/sql/';
    }
    public function json($data){
        
        $this->_helper->json($data);
    }
    public function fluxoCaixaAction(){
        $data = $this->db->fetchAll(file_get_contents($this->path.'fluxo_caixa.sql'));
        $this->json($data);
    }
    public function formaPagamentoAction(){
        $data = $this->db->fetchAll(file_get_contents($this->path.'forma_pagamento.sql'));
        $this->json($data);
    }
    public function indexadoresAction(){
        $data = $this->db->fetchAll(file_get_contents($this->path.'indexadores.sql'));
        $this->json($data);
    }
}
