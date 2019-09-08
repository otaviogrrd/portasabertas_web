<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Equipamentos extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_equipamentos';
    
    
 public function relatorioEquipamentos() {
        //$db = Zend_Db_Table::getDefaultAdapter();
       // $cliente = new Application_Model_DbTable_Cliente();
        $select = $this->select()
			        	->setIntegrityCheck(false)
			            ->from('tb_equipamentos')
			            ->joinLeft('tb_barracas', 'tb_equipamentos.id_barraca = tb_barracas.id_barraca')
                        ->order('tb_equipamentos.st_validado asc')
                        ->order('tb_equipamentos.id_equipamento desc');
        return $this->fetchAll($select);
    }
    

    public function verificarEquipamentoCadastrado($hash, $somenteAtivos = FALSE){
        $query = $this->select()
                      ->where('mac = ?', $hash);
        if ($somenteAtivos){
            $query->where('st_validado = ?', 1);
        }
                       
        $result = $query->query()
                        ->fetchAll();
        if (count($result) == 0)
            throw new Exception('Equipamento não cadastrado. Entre em contato com a equipe de TI.');
    }

    public function ativarEquipamento($id){
        $eqp = $this->find($id);
        if ($eqp->count() != 1){
            throw new Exception('Não foi possível encontrar o equipamento informado.');
        }
        $eqp = $eqp->current();
        $eqp->st_validado = ($eqp->st_validado)?0:1;
        $eqp->save();
        return "Equipamento atualizado com sucesso!";
    }
}
