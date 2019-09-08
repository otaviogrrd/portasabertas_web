<?php
/**
 * Usuarios
 * 
 * @author Marcus
 * @version 
 */
class Application_Model_Vendedor extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_vendedores';

    public function insert(array $data){
    	$barracas = $data['id_barraca'];
    	unset($data['id_barraca']);
    	$id = parent::insert($data);
    	$vendedorBarraca = new Application_Model_VendedorBarraca();
    	foreach ($barracas as $este) {
    		$vendedorBarraca->insert(
	    		array(
	    			'id_barraca' => $este,
	    			'id_vendedor' => $id
	    		)
	    	);
    	}
    	return $id;
    }

}
