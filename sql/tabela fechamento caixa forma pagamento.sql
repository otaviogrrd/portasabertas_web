CREATE TABLE IF NOT EXISTS `e_sac5`.`tb_fechamento_caixa_forma_pagamento` (
  `id_fechamento_caixa` INT(11) NOT NULL,
  `id_forma_pagamento` INT(11) NOT NULL,
  `nu_esperado` FLOAT ZEROFILL NULL,
  `nu_obtido` FLOAT ZEROFILL NULL,
  PRIMARY KEY (`id_fechamento_caixa`, `id_forma_pagamento`)
  );
  
CREATE TABLE `e_sac5`.`tb_vendedor_barraca` (
  `id_vendedor` INT NOT NULL COMMENT '',
  `id_barraca` INT NOT NULL COMMENT '',
  PRIMARY KEY (`id_vendedor`, `id_barraca`)  COMMENT '');


CREATE TABLE `tb_vendedor_barraca` (
  `id_vendedor` int(11) NOT NULL,
  `id_barraca` int(11) NOT NULL,
  PRIMARY KEY (`id_vendedor`,`id_barraca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
