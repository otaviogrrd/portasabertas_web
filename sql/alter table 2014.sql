/*
ALTER TABLE `e_sac5`.`tb_equipamentos` ADD COLUMN `st_validado` BINARY NULL DEFAULT FALSE  AFTER `responsavel` ;
ALTER TABLE `e_sac5`.`tb_equipamentos` CHANGE COLUMN `descricao` `descricao` VARCHAR(300) NULL DEFAULT NULL  ;
ALTER TABLE `e_sac5`.`tb_equipamentos` CHANGE COLUMN `id_barraca` `id_barraca` INT(11) NULL  ;
delimiter $$

CREATE TABLE `tb_fechamento_caixa` (
  `id_fechamento_caixa` int(11) NOT NULL AUTO_INCREMENT,
  `id_responsavel_fechamento` int(11) NOT NULL,
  `dt_fechamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_fechamento_caixa`),
  KEY `fk_responsavel_fechamento_idx` (`id_responsavel_fechamento`),
  CONSTRAINT `fk_responsavel_fechamento` FOREIGN KEY (`id_responsavel_fechamento`) REFERENCES `tb_vendedores` (`id_vendedor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$
ALTER TABLE `e_sac5`.`tb_creditos` ADD COLUMN `id_fechamento_caixa` INT NULL  AFTER `id_estorno` , 
  ADD CONSTRAINT `fk_fechamento_caixa`
  FOREIGN KEY (`id_fechamento_caixa` )
  REFERENCES `e_sac5`.`tb_fechamento_caixa` (`id_fechamento_caixa` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_fechamento_caixa_idx` (`id_fechamento_caixa` ASC) ;

insert into tb_produtos values(-1,'Doação - Baixa de Cartão', 0.01, 10, 0, 0); 
insert into tb_eventos values(7, 'Limpeza da base');

DELIMITER $$

USE `e_sac5`$$
DROP TRIGGER IF EXISTS `e_sac5`.`tg_credito_delete` $$
DROP TRIGGER IF EXISTS `e_sac5`.`tb_vendas_delete` $$
DELIMITER ;

ALTER TABLE `e_sac5`.`tb_barracas` ADD COLUMN `aceita_pagamento` BINARY NOT NULL DEFAULT 0  AFTER `nome` ;


*/


