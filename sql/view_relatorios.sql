create or replace view vw_forma_pagamento as SELECT sum(valor) valor, count(*) quantidade, descricao FROM e_sac5.tb_creditos c
inner join tb_forma_pagamento fp on fp.id_fp = c.id_forma
where status=1 and id_Estorno = 0
group by id_forma;
select * from tb_creditos