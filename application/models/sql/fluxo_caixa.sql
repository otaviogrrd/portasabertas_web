SELECT 
    FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(dthr) / (15 * 60)) * (15 * 60)) hora,
    SUM(IF(id_forma IN (1 , 2, 3, 4, 5),
        valor,
        0)) AS entrada,
    SUM(IF(id_forma NOT IN (1 , 2, 3, 4, 5),
        valor,
        0)) AS saida
FROM
    tb_creditos
GROUP BY hora;
