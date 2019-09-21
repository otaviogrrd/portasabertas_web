<?php
/**
 * Cartao
 * 
 * @author Marcus
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Application_Model_Cartao extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tb_cartoes';
    public function pesquisar($busca){
        $auth = Zend_Auth::getInstance ();
        $login = $auth->getIdentity();
        
        try {
           return $this->pesquisarNumeroCartao($busca);  
         } catch (Exception $e) {
             if ($e->getMessage() == 'valor invalido'){
                return $this->pesquisarNomeDocumento($busca);
             }else{
                throw $e;
             }
         } 


    }
    private function pesquisarNomeDocumento($busca){
        return $this->select()
                    ->orWhere('lower(descricao) like ?', '%'.strtolower($busca).'%')
                    ->orWhere('lower(documento) like ?', '%'.strtolower($busca).'%')
                    ->order('id_cartao desc')
                    ->query()
                    ->fetchAll();
    }
    private function pesquisarNumeroCartao($busca){
        $cartao = FALSE;
        if (strlen($busca) == 12 and is_numeric($busca)){ //cartao com numeracao completa
            $cartao = (int) substr($busca,0,-1);
        }
        if (strlen($busca) == 9 and is_numeric($busca)){ //cartao sem os leading zeros
            $cartao = (int) substr($busca,0,-1);
        }
        if (strlen($busca) == 8 and is_numeric($busca)){ //cartao sem os leading zeros e sem o DV
            $cartao = (int) $busca;
        }

        if (FALSE === $cartao)
            throw new Exception("valor invalido", 1);   
        return $this->select()
                    ->where('id_cartao = ?', $cartao)
                    ->query()
                    ->fetchAll();
    
    }
    
 public function ListaCartoes() {
 	$auth = Zend_Auth::getInstance ();
	$login = $auth->getIdentity();
 	 if($login->nivel == 1 ){
        $select = $this->select()
			        	->setIntegrityCheck(false)
			            ->from('tb_cartoes')
			            ->limit(5000);
			            
        return $this->fetchAll($select);
 	 	}
    }
    public function inserirCartoes($arquivo){
        $cartoes = file($arquivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
        $positivo = 0;
        $negativo = 0;

        foreach ($cartoes as $cartao) {
            if (preg_match('#^\d{8}$#', $cartao)){
                $this->insert(
                    array(
                        'id_cartao'=>$cartao,
                        'creditos'=>0,
                        'descricao'=>'DISPONIVEL',
                        'status'=>0
                    )
                );
                $positivo++;
            }else{
                $negativo++;
            }
        }
        return "Foram inseridos na tabela de cartões (tb_cartoes):" . number_format($positivo, 0, ',','.') 
             . ". Não foram inseridos (por não serem números de 8 dígitos): " . number_format($negativo, 0, ',','.')  . ' registros.';
    }
}
