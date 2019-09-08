<?php
class Zend_View_Helper_Vendedor extends Zend_View_Helper_Abstract
{
    private $_cache = array();

    public function vendedor($nome){
        $validator = new Zend_Validate_Int();
        if(!$validator->isValid($nome))
            return $nome;
        if (count($this->_cache) == 0){
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $this->_cache = $db->fetchPairs(
                'select id_vendedor, login from tb_vendedores order by id_vendedor asc'
            );
        }
        if (array_key_exists($nome, $this->_cache))
            return $this->_cache[$nome];
    }

}