<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Barracas extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_barracas';
    
    
 public function fetchPair() {
        $db = Zend_Db_Table::getDefaultAdapter();
       // $cliente = new Application_Model_DbTable_Cliente();
        $select = $this->select()
            ->from('tb_barracas', array('id_barraca', 'nome'))
            ->order('nome');
            
        return $db->fetchPairs($select);
    }
    
 public function ListaBarracas($cookie) {
 	$auth = Zend_Auth::getInstance ();
	$login = $auth->getIdentity();
    
	//código desativado para testes

 	 if($login->nivel == 1 || $login->nivel == 4 ){
        $select = $this->select()
			        	->setIntegrityCheck(false)
			            ->from('tb_barracas');
 	 	}
 	 else{ 	 	
        $select = $this->select()
			        	->setIntegrityCheck(false)
			            ->from('tb_barracas')
			            ->join('tb_vendedor_barraca', 'tb_vendedor_barraca.id_barraca = tb_barracas.id_barraca')
                        ->where('tb_vendedor_barraca.id_vendedor=?' , $login->id_vendedor);

 	 }   
        return $this->fetchAll($select);
    }
    
}
