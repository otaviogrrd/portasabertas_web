<?php
class Application_Model_Auditoria extends Zend_Db_Table_Abstract
{
    protected $_name = 'tb_auditoria';

    public function limpar($responsavel, $id, $limparCartoes = TRUE){
        
        $passo = 1;

        $log[] =  "Passo: " . $passo++ . " Limpeza de tabelas iniciado por: " . $responsavel->login . ". Em: " . date('d/m/Y H:i:s');

        $qtd = $this->_db->delete(
            'tb_creditos'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de créditos(tb_creditos) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";
        
        $qtd = $this->_db->delete(
            'tb_fechamento_caixa'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de fechamento de caixa(tb_fechamento_caixa) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";
        
        $qtd = $this->_db->delete(
            'tb_caixas'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de caixas(tb_caixas) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";

        $qtd = $this->_db->delete(
            'tb_vendedores',
            'id_vendedor not in (134,139,454,455)'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de vendedores(tb_vendedores) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";

        $qtd = $this->_db->delete(
            'tb_vendas'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de vendas(tb_vendas) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";


        $qtd = $this->_db->delete(
            'tb_produtos',
            $this->_db->quoteInto('id_produtos > ?', 0)
        );

        $log[] ="Passo: " . $passo++ . " Tabela de produtos(tb_produtos) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";

        $qtd = $this->_db->delete(
            'tb_barracas'
        );

        $log[] ="Passo: " . $passo++ . " Tabela de barracas(tb_barracas) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";

        $qtd = $this->_db->delete(
            'tb_equipamentos',
            $this->_db->quoteInto('responsavel <> ?', $responsavel->id_vendedor)
        );

        $log[] ="Passo: " . $passo++ . " Tabela de equipamentos(tb_equipamentos) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";
        if ($limparCartoes){
            $qtd = $this->_db->delete(
                'tb_cartoes'
            );

            $log[] ="Passo: " . $passo++ . " Tabela de cartões(tb_cartoes) limpada, foram apagados: " . number_format($qtd, 0, ',', '.') . " registros";
        }



        return implode("\n", $log);
    }
    public function resetCartoes(){
        $qtd = $this->_db->update(
                'tb_cartoes',
                array(
                    'creditos' => 0,
                    'descricao' => 'DISPONIVEL',
                    'documento' => new Zend_Db_Expr('null'),
                    'status' => 0
                )
            );
        return $qtd;
    }
}
