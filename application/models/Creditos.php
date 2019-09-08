<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Creditos extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_creditos';

    public function getDatasComCreditos(){
    return $this->getAdapter()->fetchAll(
        "select distinct date_format(dthr, '%d/%m/%Y') as label, date_format(dthr, '%Y-%m-%d') as code from tb_creditos;"
    );
}    
public function relatorioReceita($filtro) {
 		if ($filtro != "")
	 		{
	 		if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
	 		$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i',3=>'/DIA/i');
			$replacements= array (0=> 'cd.id_cartao',1=>'cd.id_caixa',2=>'cd.id_vendedor',3=>'date(cd.dthr)');
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
	 		}
 		else
 			{
 			$where='cd.id_entrada is not null';
 			}       
        #$select = $this->select(id_cartao,dthr,tb.creditors.valor as valor1,tb_caixas.descricao as caixa, tb_forma_pagamento.descricao as forma)
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('cd' => 'tb_creditos'))
                        ->join(array('ct' => 'tb_cartoes'), 'cd.id_cartao = ct.id_cartao',array('Portador' => 'descricao'))
                        ->joinLeft(array('ca' => 'tb_caixas'), 'cd.id_caixa = ca.id_caixa',array('Caixa' => 'descricao'))
                        ->join(array('fp' => 'tb_forma_pagamento'), 'cd.id_forma = fp.id_fp',array('Forma' => 'descricao'))
                        ->joinLeft(array('vd' => 'tb_vendedores'), 'cd.id_vendedor = vd.id_vendedor')
                        ->where($where)
                        ->order('cd.dthr DESC');
                        //->group(array('cd.id_caixa'));
                        //->where('users_id=?', $user_id)

        return $this->fetchAll($select);
    }

public function relatorioReceitaCaixa($filtro) {
		if ($filtro != "")
	 		{
	 		if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
	 		$patterns = array(
                0=> '/CARTAO/i',
                1=> '/CAIXA/i', 
                2=>'/VENDEDOR/i',
                3=>'/DIA/i'
            );
			$replacements= array (
                0=> 'cd.id_cartao',
                1=>'cd.id_caixa',
                2=>'cd.id_vendedor',
                3=>'date(cd.dthr)'
            );
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
	 		}
 		else
 			{
 			$where='cd.id_entrada is not null';
 			}      
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('cd' => 'tb_creditos'),array(  "total" => "sum(valor)",
        															"qtd" => "count(*)"))
                        ->join(array('ca' => 'tb_caixas'), 'cd.id_caixa = ca.id_caixa',array('Caixa' => 'descricao'))
                        ->join(array('fp' => 'tb_forma_pagamento'), 'cd.id_forma = fp.id_fp',array('Forma' => 'descricao'))
                        ->join(array('vd' => 'tb_vendedores'), 'cd.id_vendedor = vd.id_vendedor')
                        ->where($where)
                        ->where('cd.status = 1')
                        //->where('cd.id_fechamento_caixa is  null')
                        ->where('cd.id_forma <> 5')
                        ->group(array('cd.id_caixa','cd.id_forma',));
        
                        
        return $this->fetchAll($select);
    }

public function relatorioReceitaHora($filtro) {
		if ($filtro != "")
	 		{
	 		$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i',3=>'/DIA/i');
			$replacements= array (0=> 'cd.id_cartao',1=>'cd.id_caixa',2=>'cd.id_vendedor',3=>'date(cd.dthr)');
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
	 		}
 		else
 			{
 			$where='cd.id_entrada is not null';
 			}      
        #$select = $this->select(id_cartao,dthr,tb.creditors.valor as valor1,tb_caixas.descricao as caixa, tb_forma_pagamento.descricao as forma)
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('cd' => 'tb_creditos'),array(  "total" => "sum(valor)",
        															"qtd" => "count(*)",
                        											"hora" => "HOUR(dthr)"))
                        ->join(array('ct' => 'tb_cartoes'),'cd.id_cartao = ct.id_cartao',array('Portador' => 'descricao'))
                        ->join(array('ca' => 'tb_caixas'), 'cd.id_caixa = ca.id_caixa',array('Caixa' => 'descricao'))
                        ->join(array('fp' => 'tb_forma_pagamento'), 'cd.id_forma = fp.id_fp',array('Forma' => 'descricao'))
                        ->join(array('vd' => 'tb_vendedores'), 'cd.id_vendedor = vd.id_vendedor')
                        ->where($where)
                        ->group(array('hora','cd.id_forma'));
                        //->where('users_id=?', $user_id)
        return $this->fetchAll($select);
    }

public function relatorioReceitaMoeda($filtro) {
		if ($filtro != "")
	 		{
	 		$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i', 3=>'/DIA/i');
			$replacements= array (0=> 'cd.id_cartao',1=>'cd.id_caixa',2=>'cd.id_vendedor',3=>'date(cd.dthr)');
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
	 		}
 		else
 			{
 			$where='cd.id_entrada is not null';
 			}      
        #$select = $this->select(id_cartao,dthr,tb.creditors.valor as valor1,tb_caixas.descricao as caixa, tb_forma_pagamento.descricao as forma)
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('cd' => 'tb_creditos'),array(  "total" => "sum(valor)",
        															"qtd" => "count(*)"))
                        ->join(array('ca' => 'tb_caixas'), 'cd.id_caixa = ca.id_caixa',array('Caixa' => 'descricao'))
                        ->join(array('fp' => 'tb_forma_pagamento'), 'cd.id_forma = fp.id_fp',array('Forma' => 'descricao'))
                        ->join(array('vd' => 'tb_vendedores'), 'cd.id_vendedor = vd.id_vendedor')
                        ->where($where)
                        ->group(array('cd.id_forma'));
                        //->where('users_id=?', $user_id)
        return $this->fetchAll($select);
    }
public function relatorioFechamentoCaixa($filtro) {
		if ($filtro != "")
	 		{
	 		if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
	 		$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i');
			$replacements= array (0=> 'cd.id_cartao',1=>'cd.id_caixa',2=>'cd.id_vendedor');
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
	 		}
 		else
 			{
 			$where='cd.id_entrada is not null and cd.status=1';
 			}      
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('cd' => 'tb_creditos'),array(  "total" => "sum(valor)",
        															"qtd" => "count(*)"))
                        ->join(array('ca' => 'tb_caixas'), 'cd.id_caixa = ca.id_caixa',array('id_caixa'=>'id_caixa','Caixa' => 'descricao','localizacao' => 'localizacao','ip' => 'ip'))
                        ->join(array('vd' => 'tb_vendedores'), 'cd.id_vendedor = vd.id_vendedor')
                        ->where($where)
                        ->where('cd.id_forma <> 5')
                        ->group(array('cd.id_caixa','cd.id_vendedor',));
        
        return $this->fetchAll($select);
    }
    public function getCreditosPorTipo($cartao){
        return $this->getAdapter()->fetchPairs(
            "select id_forma, sum(valor) from tb_creditos where id_cartao = ? group by id_forma",
            $cartao
        );
        
    }
}
    
    
    			