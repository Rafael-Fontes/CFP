-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.6.25-log - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura do banco de dados para cfp
CREATE DATABASE IF NOT EXISTS `cfp` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `cfp`;


-- Copiando estrutura para tabela cfp.cartoes_credito
CREATE TABLE IF NOT EXISTS `cartoes_credito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_conta` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `limite` decimal(10,2) NOT NULL,
  `dia_pagamento` datetime NOT NULL,
  `dia_fechamento` datetime NOT NULL,
  `situacao` enum('A','I') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`,`fk_conta`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_cartoes_credito_contas1_idx` (`fk_conta`),
  CONSTRAINT `fk_cartoes_credito_contas1` FOREIGN KEY (`fk_conta`) REFERENCES `contas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL COMMENT 'Esse valor é unido a senha para que seja gerado um hash',
  `token` varchar(255) DEFAULT NULL COMMENT 'Valor usado na ativação do cliente',
  `criado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `situacao` enum('A','I') NOT NULL DEFAULT 'I' COMMENT 'A => Ativo\nI => Inativo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.contas
CREATE TABLE IF NOT EXISTS `contas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_tipo` int(11) NOT NULL,
  `fk_cliente` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `saldo` decimal(10,2) DEFAULT NULL COMMENT 'Saldo do inicil do mês',
  `criado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`,`fk_tipo`,`fk_cliente`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_contas_table11_idx` (`fk_tipo`),
  KEY `fk_contas_clientes1_idx` (`fk_cliente`),
  CONSTRAINT `fk_contas_clientes1` FOREIGN KEY (`fk_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_contas_table11` FOREIGN KEY (`fk_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.despesas
CREATE TABLE IF NOT EXISTS `despesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_categoria` int(11) NOT NULL,
  `fk_subcategoria` int(11) DEFAULT NULL COMMENT 'Não é obrigatório ter subcategorias.',
  `fk_cliente` int(11) NOT NULL,
  `fk_conta` int(11) NOT NULL,
  `fk_cartao` int(11) DEFAULT NULL,
  `descricao` varchar(50) NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `repetir` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'O pagamento é unico ou vai se repetir outras vezes?\n\nS => SIM\nN => NAO',
  `repetir_quando` enum('DIA','SEM','MES','ANO') DEFAULT NULL COMMENT 'Se o pagamento for se repetir, vai ser com qual frequência?\n\nDIA => Dia\nSEM => Semana\nMES => Mês\nANO => Ano',
  `repetir_ocorrencia` int(11) DEFAULT NULL COMMENT 'Por quantas vezes o pagamento ira se repetir.\nEX: 1, 2, 6 ou 12 vezes',
  `data_fatura` date DEFAULT NULL COMMENT 'Quando a despesa tiver sido feita via cartão de credito, esse campo torna-se obrigátorio.\nCampo corresponde a data de fechamento da fatura.',
  `pagamento` enum('S','N') NOT NULL DEFAULT 'N' COMMENT 'Identifica se o pagamento já foi efetivado.\n\nS => SIM\nN => NAO',
  `pagamento_data` date DEFAULT NULL,
  `pagamento_desconto` decimal(10,2) DEFAULT NULL,
  `pagamento_juro` decimal(10,2) DEFAULT NULL,
  `pagamento_valor` decimal(10,2) DEFAULT NULL,
  `grupo` varchar(50) DEFAULT NULL COMMENT 'Campo indentifica as despesas associadas. Esse valor corresponde ao ID da primeira despesa do grupo.',
  `anexo` varchar(255) DEFAULT NULL COMMENT 'Adicionar arquivos, como por exemplo  o comprovante de pagamento de uma fatura.',
  `obs` varchar(255) DEFAULT NULL,
  `criado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`,`fk_categoria`,`fk_cliente`,`fk_conta`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_despesas_despesas_categorias1_idx` (`fk_categoria`),
  KEY `fk_despesas_despesas_subcategorias1_idx` (`fk_subcategoria`),
  KEY `fk_despesas_clientes1_idx` (`fk_cliente`),
  KEY `fk_despesas_contas1_idx` (`fk_conta`),
  KEY `fk_despesas_cartoes_credito1_idx` (`fk_cartao`),
  CONSTRAINT `fk_despesas_cartoes_credito1` FOREIGN KEY (`fk_cartao`) REFERENCES `cartoes_credito` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_despesas_clientes1` FOREIGN KEY (`fk_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_despesas_contas1` FOREIGN KEY (`fk_conta`) REFERENCES `contas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_despesas_despesas_categorias1` FOREIGN KEY (`fk_categoria`) REFERENCES `despesas_categorias` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_despesas_despesas_subcategorias1` FOREIGN KEY (`fk_subcategoria`) REFERENCES `despesas_subcategorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.despesas_categorias
CREATE TABLE IF NOT EXISTS `despesas_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_cliente` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  PRIMARY KEY (`id`,`fk_cliente`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_despesas_categorias_clientes1_idx` (`fk_cliente`),
  CONSTRAINT `fk_despesas_categorias_clientes1` FOREIGN KEY (`fk_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.despesas_subcategorias
CREATE TABLE IF NOT EXISTS `despesas_subcategorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorias_id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL COMMENT 'Subcategoria deve ser unica para cada categoria.',
  PRIMARY KEY (`id`,`categorias_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_despesas_subcategorias_despesas_categorias1_idx` (`categorias_id`),
  CONSTRAINT `fk_despesas_subcategorias_despesas_categorias1` FOREIGN KEY (`categorias_id`) REFERENCES `despesas_categorias` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.permicoes
CREATE TABLE IF NOT EXISTS `permicoes` (
  `id` int(11) NOT NULL,
  `fk_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id`,`fk_perfil`),
  KEY `fk_permicoes_perfis1_idx` (`fk_perfil`),
  CONSTRAINT `fk_permicoes_perfis1` FOREIGN KEY (`fk_perfil`) REFERENCES `perfis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.receitas
CREATE TABLE IF NOT EXISTS `receitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_categoria` int(11) NOT NULL,
  `fk_subcategoria` int(11) DEFAULT NULL,
  `fk_cliente` int(11) NOT NULL,
  `fk_conta` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `data_vencimento` date NOT NULL COMMENT 'Corresponde a data de vencimento da receita cujo pagamento ainda não foi recebido.\nEsse campo é ultilizado para fazer a listagem das receitas por data.',
  `valor` decimal(10,2) DEFAULT NULL,
  `repetir` enum('S','N') DEFAULT NULL COMMENT 'O pagamento é unico ou vai se repetir outras vezes?\n\nS => SIM\nN => NAO',
  `repetir_quando` enum('DIAR','MENS','BIME','TRIM','SEME','ANUA') DEFAULT NULL COMMENT 'Se o pagamento for se repetir, vai ser com qual frequência?\n\nDIAR => Diariamento\nSEME => Semanalmente\nMENS => Mensalmente\nBIME => Bimestralmente\nTRIM => Trimestralmente\nSEME => Semestralmente\nANUA => Anualmente',
  `repetir_ocorrencia` int(11) DEFAULT NULL COMMENT 'Por quantas vezes o pagamento ira se repetir.\nEX: 1, 2, 6 ou 12 vezes',
  `pagamento` enum('S','N') DEFAULT NULL,
  `pagamento_data` date DEFAULT NULL,
  `pagamento_desconto` decimal(10,2) DEFAULT NULL,
  `pagamento_juro` decimal(10,2) DEFAULT NULL,
  `pagamento_valor` decimal(10,2) DEFAULT NULL,
  `anexo` varchar(45) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL,
  `criado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  PRIMARY KEY (`id`,`fk_categoria`,`fk_cliente`,`fk_conta`),
  KEY `fk_contas_areceber_contas_areceber_categorias1_idx` (`fk_categoria`),
  KEY `fk_receitas_receitas_subcategorias1_idx` (`fk_subcategoria`),
  KEY `fk_receitas_clientes1_idx` (`fk_cliente`),
  KEY `fk_receitas_contas1_idx` (`fk_conta`),
  CONSTRAINT `fk_contas_areceber_contas_areceber_categorias1` FOREIGN KEY (`fk_categoria`) REFERENCES `receitas_categorias` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_receitas_clientes1` FOREIGN KEY (`fk_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_receitas_contas1` FOREIGN KEY (`fk_conta`) REFERENCES `contas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_receitas_receitas_subcategorias1` FOREIGN KEY (`fk_subcategoria`) REFERENCES `receitas_subcategorias` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.receitas_categorias
CREATE TABLE IF NOT EXISTS `receitas_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_cliente` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  PRIMARY KEY (`id`,`fk_cliente`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_receitas_categorias_clientes1_idx` (`fk_cliente`),
  CONSTRAINT `fk_receitas_categorias_clientes1` FOREIGN KEY (`fk_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.receitas_subcategorias
CREATE TABLE IF NOT EXISTS `receitas_subcategorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_categoria` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL COMMENT 'Subcategoria deve ser unica para cada categoria.',
  PRIMARY KEY (`id`,`fk_categoria`),
  UNIQUE KEY `idcontas_pagar_subcategorias_UNIQUE` (`id`),
  KEY `fk_contas_receber_subcategorias_contas_receber_categorias1_idx` (`fk_categoria`),
  CONSTRAINT `fk_contas_receber_subcategorias_contas_receber_categorias1` FOREIGN KEY (`fk_categoria`) REFERENCES `receitas_categorias` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.tipos
CREATE TABLE IF NOT EXISTS `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela cfp.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_perfil` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `senha` varchar(40) NOT NULL,
  `criado` datetime NOT NULL,
  `modificado` datetime NOT NULL,
  `situacao` enum('A','I') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`,`fk_perfil`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_usuarios_perfis1_idx` (`fk_perfil`),
  CONSTRAINT `fk_usuarios_perfis1` FOREIGN KEY (`fk_perfil`) REFERENCES `perfis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
