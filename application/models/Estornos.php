<?php
/**
 * Cartao
 * 
 * @author Marcus
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Estornos extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_tipo_estorno';
 public function fetchPair() {
        $db = Zend_Db_Table::getDefaultAdapter();
       // $cliente = new Application_Model_DbTable_Cliente();
        $select = $this->select()
            ->from('tb_tipo_estorno', array('id_estorno', 'descricao'))
            ->order('descricao');
            
        return $db->fetchPairs($select);
    }
    
}
