<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Caixas extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
	protected $_name = 'tb_caixas';
	

	public function relatorioCaixas() {
       
        $select = $this->select() ; 

        return $this->fetchAll($select);
    }
    
	
	
}

