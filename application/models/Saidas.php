<?php
/**
 * Produtos
 * 
 * @author Marcus
 * @version 
 */

class Application_Model_Saidas extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_vendas';

  
public function getDatasComSaidas(){
    return $this->getAdapter()->fetchAll(
        "select distinct date_format(dthr, '%d/%m/%Y') as label, date_format(dthr, '%Y-%m-%d') as code from tb_vendas;"
    );
}
public function relatorioSaida($filtro) {
        if ($filtro != "")
			{
			if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
			$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i', 3=>'/PRODUTO/i', 4=>'/STATUS/i', 5=>'/BARRACA/i', 6=>'/DIA/i');
			$replacements= array (0=> 'vd.id_cartao',1=>'id_caixa',2=>'vd.id_vendedor',3=>'vd.id_produto',4=>'status',5=>'barraca',6=>'date(vd.dthr)');
			$where= "(". preg_replace($patterns, $replacements, $filtro).")";
			}
 		else
 			{
 			$where='vd.id_venda is not null';
 			} 
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from(array('vd' => 'tb_vendas'))
                        ->join(array('ct' => 'tb_cartoes'), 'vd.id_cartao = ct.id_cartao',array('Portador' => 'descricao'))
                        ->join(array('pd' => 'tb_produtos'), 'vd.id_produto = pd.id_produtos',array('valor_atual' => 'valor','descricao'=>'descricao'))
                        ->join(array('ve' => 'tb_vendedores'), 've.id_vendedor = vd.id_vendedor')
                        ->joinLeft(array('ba' => 'tb_barracas'), 'ba.id_barraca = pd.barraca')
                        ->where($where)
                        ->order('vd.dthr DESC')
                        ->limit(5000);
                        
                        //->where('users_id=?', $user_id)
        
        return $this->fetchAll($select);
    }    

public function relatorioSaidaBarraca($filtro) {
	   	$auth = Zend_Auth::getInstance ();
		$login = $auth->getIdentity();
    	$permissoes=array(1,2,4);
        if ($filtro != "")
			{
			if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
	 		if ($login->nivel !=1)
	 			$filtro=preg_replace("/ or /"," and ",$filtro);
			$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i', 3=>'/PRODUTO/i', 4=>'/STATUS/i', 5=>'/BARRACA/i',6=>'/DIA/i');
			$replacements= array (0=> 'tb_vendas.id_cartao',1=>'id_caixa',2=>'tb_vendas.id_vendedor',3=>'tb_vendas.id_produto',4=>'status',5=>'barraca',6=>'date(tb_vendas.dthr)');
			$where= "(tb_vendas.status=1 and ". preg_replace($patterns, $replacements, $filtro).")";
			}
 		else
 			{
 			$where='tb_vendas.status=1 ';
 			} 
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from('tb_vendas',array(  "total" => "sum(tb_vendas.valor)",
        											"qtdv" => "sum(tb_vendas.qtd)", 'id_produto'))
                        ->join('tb_produtos', 'tb_vendas.id_produto = tb_produtos.id_produtos', array('barraca', "descricao"))
                        ->join('tb_vendedores', 'tb_vendedores.id_vendedor = tb_vendas.id_vendedor', array())
                        ->join('tb_barracas', 'tb_barracas.id_barraca = tb_produtos.barraca', array("nome"))
                        ->where($where)
                        ->where('status=1')
                        ->group('tb_produtos.barraca')
                        ->group('tb_vendas.id_produto');
                        
                        //->where('users_id=?', $user_id) 100622928

        return $this->fetchAll($select);
    }    
public function relatorioSaidaHora($filtro) {
        if ($filtro != "")
			{
			if (!preg_match("/=/",$filtro))
	 			$filtro="CARTAO=".$filtro;
			$patterns = array(0=> '/CARTAO/i',1=> '/CAIXA/i', 2=>'/VENDEDOR/i', 3=>'/PRODUTO/i', 4=>'/STATUS/i', 5=>'/BARRACA/i',6=>'/DIA/i');
			$replacements= array (0=> 'tb_vendas.id_cartao',1=>'id_caixa',2=>'tb_vendas.id_vendedor',3=>'tb_vendas.id_produto',4=>'status',5=>'barraca',6=>'date(tb_vendas.dthr)');
			$where= "(tb_vendas.status=1 and ". preg_replace($patterns, $replacements, $filtro).")";
			}
 		else
 			{
 			$where='tb_vendas.status=1';
 			} 
        $select = $this->select()
                        ->setIntegrityCheck(false)
                        ->from('tb_vendas',array(  "total" => "sum(tb_vendas.valor)",
        											"qtdv" => "sum(tb_vendas.qtd)",
                        							"hora" => "HOUR(dthr)"))
                        ->join('tb_produtos', 'tb_vendas.id_produto = tb_produtos.id_produtos')
                        ->join('tb_vendedores', 'tb_vendedores.id_vendedor = tb_vendas.id_vendedor')
                        ->join('tb_barracas', 'tb_barracas.id_barraca = tb_produtos.barraca')
                        ->where($where)
                        ->where('status=1')
                        ->group('hora')
                        ->group('tb_produtos.barraca')
                        ->group('tb_vendas.id_produto');
                        
                        //->where('users_id=?', $user_id)

        return $this->fetchAll($select);
    }    
  
public function relatorioporbarraca($id_barraca,$id_cartao) {
    $where = array('tb_vendas.status = 1');
    if(preg_match("/\d+/",$id_barraca) && $id_barraca > 0)
        $where[] = $this->getAdapter()->quoteInto(
            "tb_produtos.barraca=?",
            $id_barraca
        );
	if(preg_match("/\-?\d+/",$id_cartao))
        $where[] = $this->getAdapter()->quoteInto(
            "tb_vendas.id_cartao=?",
            $id_cartao
        );
	
	$cols = array(
        'id_venda'=> 'id_venda',
        'id_produto'=> 'id_produto',
        'id_vendedor'=> 'id_vendedor',
        'id_cartao'=> 'id_cartao',
        'total' => 'valor',
        'dthr'=>'dthr',
        'qtd'=>'qtd',
        'status'=> 'status'
    );
    if ($id_cartao < 0)
        $cols['portador'] = 'tb_vendedores.login';
  	$select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from('tb_vendas',$cols)                       
                    ->join('tb_produtos', 'tb_vendas.id_produto = tb_produtos.id_produtos')
                    ->join('tb_vendedores', 'tb_vendedores.id_vendedor = tb_vendas.id_vendedor')                    
                    ->where(implode(" AND ", $where))
                    ->order('dthr DESC');
    if ($id_cartao > 0)
        $select->join('tb_cartoes', 'tb_vendas.id_cartao = tb_cartoes.id_cartao',array('portador'=> 'descricao'));
    
    return $this->fetchAll($select);
	}   
 
}    
