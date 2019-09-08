<?php
/**
 * Arquivo models/validate/Cpf.php
 */

class MinhaBiblioteca_Resource implements Zend_Acl_Resource_Interface
{
    public $controller, $action, $label, $key, $id;

	public function __construct($id, $params){
        $this->id = $id;
        $this->controller = $params->controller;
        $this->action = $params->action;
        $this->label = $params->label;
        $this->key = $params->key;
    }

    public function getResourceId(){
        return $this->id;
    }
}