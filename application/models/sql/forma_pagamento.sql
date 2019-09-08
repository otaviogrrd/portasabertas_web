SELECT 
    descricao, SUM(IF(valor < 0, valor * - 1, valor)) AS valor
FROM
    tb_creditos c
        INNER JOIN
    tb_forma_pagamento fp ON c.id_forma = fp.id_fp
GROUP BY descricao