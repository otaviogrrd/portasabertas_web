<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Produtos extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
	protected $_name = 'tb_produtos';
	
	public function relatorioProdutos() {
       
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from('tb_produtos')
                        ->join('tb_barracas', 'tb_produtos.barraca = tb_barracas.id_barraca')
                        ->order('tb_barracas.nome')
                        ->order('tb_produtos.descricao');
                        //->where('users_id=?', $user_id)

        return $this->fetchAll($select);
    }
    
    public function getPrecos(){
        return $this->select()
                    ->where('id_produtos > 0')
                    ->query()
                    ->fetchAll();
    }
	
	
}

