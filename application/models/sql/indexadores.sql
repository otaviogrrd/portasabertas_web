SELECT 
    SUM(entrada) AS entrada,
    SUM(ativos) AS ativos,
    SUM(inativos) AS inativos,
    SUM(transacoes) AS transacoes,
    SUM(produtos) AS produtos
FROM
    (SELECT 
        SUM(valor) entrada,
            0 AS ativos,
            0 AS inativos,
            0 AS transacoes,
            0 AS produtos
    FROM
        tb_creditos
    WHERE
        id_forma <> 5 UNION SELECT 
        0, COUNT(*), 0, 0, 0
    FROM
        tb_cartoes
    WHERE
        status = 1 UNION SELECT 
        0, 0, COUNT(*), 0, 0
    FROM
        tb_cartoes
    WHERE
        status = 2 UNION SELECT 
        0, 0, 0, SUM(valor), SUM(qtd)
    FROM
        tb_vendas
    WHERE
        status = 1) a;