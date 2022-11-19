-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2022 às 02:38
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `coffee`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairros`
--

CREATE TABLE `bairros` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `cidade` varchar(20) NOT NULL DEFAULT 'Itabirito',
  `valor_entrega` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bairros`
--

INSERT INTO `bairros` (`id`, `nome`, `slug`, `cidade`, `valor_entrega`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Centro', 'centro', 'Itabirito', '5.00', 1, '2022-11-06 20:54:26', '2022-11-07 20:28:36', NULL),
(2, 'São José', 'sao-jose', 'Itabirito', '5.00', 1, '2022-11-07 20:11:07', '2022-11-07 20:27:27', NULL),
(3, 'Adão Lopes', 'adao-lopes', 'Itabirito', '5.00', 1, '2022-11-07 20:18:33', '2022-11-07 20:26:09', NULL),
(4, 'Lourdes', 'lourdes', 'Itabirito', '5.00', 1, '2022-11-12 12:58:09', '2022-11-12 12:58:09', NULL),
(5, 'Agostinho Rodrigues', 'agostinho-rodrigues', 'Itabirito', '7.00', 1, '2022-11-12 12:58:36', '2022-11-12 12:58:36', NULL),
(6, 'Novo Itabirito', 'novo-itabirito', 'Itabirito', '4.50', 1, '2022-11-12 13:23:22', '2022-11-12 13:23:22', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `slug`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(126, 'Cafés Simples', 'cafes-simples', 1, '2022-11-08 20:27:43', '2022-11-08 20:28:16', NULL),
(127, 'Cafés Especiais', 'cafes-especiais', 1, '2022-11-08 20:28:08', '2022-11-08 20:28:22', NULL),
(128, 'Salgados', 'salgados', 1, '2022-11-08 20:43:58', '2022-11-08 20:43:58', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `entregadores`
--

CREATE TABLE `entregadores` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `cnh` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `endereco` varchar(240) NOT NULL,
  `imagem` varchar(240) DEFAULT NULL,
  `veiculo` varchar(240) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `entregadores`
--

INSERT INTO `entregadores` (`id`, `nome`, `cpf`, `cnh`, `email`, `telefone`, `endereco`, `imagem`, `veiculo`, `placa`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Pedro Luiz Augusto', '071.001.660-39', '36987092326', 'pedro@email.com', '(31) 99999-9999', 'Rua um, 45, Centro, Itabirito - MG', NULL, 'Titan 150 - Preta - 2011', 'HMA-1755', 1, '2022-11-06 18:32:01', '2022-11-16 23:20:49', NULL),
(2, 'Yago de Oliveira Rodrigues Pereira', '124.502.316-09', '39882035231', 'guitoflash@gmail.com', '(31) 98809-2841', 'Francisco Jose de Carvalho 29c Loja Conexao Eletronicos', '1667776835_a055597ead14fbcb1a7d.jpg', 'Mt-03 2021', 'GPN-0611', 0, '2022-11-06 20:04:31', '2022-11-17 10:53:09', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `expediente`
--

CREATE TABLE `expediente` (
  `id` int(5) UNSIGNED NOT NULL,
  `dia` int(5) NOT NULL,
  `dia_descricao` varchar(50) NOT NULL,
  `abertura` time DEFAULT NULL,
  `fechamento` time DEFAULT NULL,
  `situacao` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `expediente`
--

INSERT INTO `expediente` (`id`, `dia`, `dia_descricao`, `abertura`, `fechamento`, `situacao`) VALUES
(1, 0, 'Domingo', '06:00:00', '20:00:00', 0),
(2, 1, 'Segunda', '06:00:00', '20:00:00', 1),
(3, 2, 'Terca', '06:00:00', '20:00:00', 1),
(4, 3, 'Quarta', '01:00:00', '20:00:00', 1),
(5, 4, 'Quinta', '06:00:00', '20:00:00', 1),
(6, 5, 'Sexta', '06:00:00', '20:00:00', 1),
(7, 6, 'Sabado', '06:00:00', '20:00:00', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `formas_pagamento`
--

CREATE TABLE `formas_pagamento` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `formas_pagamento`
--

INSERT INTO `formas_pagamento` (`id`, `nome`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Dinheiro', 1, '2022-11-06 14:10:01', '2022-11-06 14:10:01', NULL),
(2, 'Cartão de crédito', 1, '2022-11-06 16:06:46', '2022-11-06 18:18:53', NULL),
(3, 'Cartão de Débito', 1, '2022-11-06 17:45:34', '2022-11-06 18:18:24', NULL),
(4, 'PIX', 0, '2022-11-06 17:45:52', '2022-11-16 18:16:08', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `medidas`
--

CREATE TABLE `medidas` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `descricao` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `medidas`
--

INSERT INTO `medidas` (`id`, `nome`, `descricao`, `ativo`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(5, 'Copo 500ml', 'Copo 500ml', 1, '2022-11-08 20:29:25', '2022-11-08 20:30:03', NULL),
(6, 'Copo 300ml', 'Copo 300ml', 1, '2022-11-08 20:29:52', '2022-11-08 20:29:52', NULL),
(7, 'Copo 1L', 'Copo 1L', 1, '2022-11-08 20:30:24', '2022-11-08 20:30:24', NULL),
(8, 'Grande', 'Grande', 1, '2022-11-08 20:44:24', '2022-11-08 20:44:24', NULL),
(9, 'Pequeno', 'Pequeno', 1, '2022-11-08 20:44:36', '2022-11-08 20:44:36', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(3, '2022-10-04-115147', 'App\\Database\\Migrations\\CriaTabelaUsuarios', 'default', 'App', 1665108881, 1),
(4, '2022-11-02-020659', 'App\\Database\\Migrations\\CriaTabelaCategorias', 'default', 'App', 1667355070, 2),
(6, '2022-11-02-023441', 'App\\Database\\Migrations\\CriaTabelaCategorias', 'default', 'App', 1667356905, 3),
(7, '2022-11-03-025550', 'App\\Database\\Migrations\\CriaTabelaMedidas', 'default', 'App', 1667444311, 4),
(8, '2022-11-04-124407', 'App\\Database\\Migrations\\CriaTabelaProdutos', 'default', 'App', 1667567030, 5),
(9, '2022-11-05-174134', 'App\\Database\\Migrations\\CriaTabelaProdutosEspecificacoes', 'default', 'App', 1667670530, 6),
(10, '2022-11-06-165606', 'App\\Database\\Migrations\\CriaTabelaFormasPagamento', 'default', 'App', 1667753961, 7),
(11, '2022-11-06-212511', 'App\\Database\\Migrations\\CriaTabelaEntregadores', 'default', 'App', 1667770299, 8),
(12, '2022-11-06-234520', 'App\\Database\\Migrations\\CriaTabelaBairros', 'default', 'App', 1667778853, 9),
(15, '2022-11-08-094709', 'App\\Database\\Migrations\\CriaTabelaExpediente', 'default', 'App', 1667902915, 10),
(16, '2022-11-15-131547', 'App\\Database\\Migrations\\CriaTabelaPedidos', 'default', 'App', 1668519043, 11),
(17, '2022-11-17-040427', 'App\\Database\\Migrations\\CriaTabelaPedidosProdutos', 'default', 'App', 1668658115, 12);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(5) UNSIGNED NOT NULL,
  `usuario_id` int(5) UNSIGNED NOT NULL,
  `entregador_id` int(5) UNSIGNED DEFAULT NULL,
  `codigo` varchar(10) NOT NULL,
  `forma_pagamento` varchar(50) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT 0,
  `produtos` text NOT NULL,
  `valor_produtos` decimal(10,2) NOT NULL,
  `valor_entrega` decimal(10,2) NOT NULL,
  `valor_pedido` decimal(10,2) NOT NULL,
  `endereco_entrega` varchar(255) NOT NULL,
  `observacoes` varchar(255) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `entregador_id`, `codigo`, `forma_pagamento`, `situacao`, `produtos`, `valor_produtos`, `valor_entrega`, `valor_pedido`, `endereco_entrega`, `observacoes`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(15, 5, 1, '95316487', 'Cartão de crédito', 2, 'a:1:{i:0;a:6:{s:2:\"id\";s:1:\"5\";s:4:\"nome\";s:17:\"Mocaccino Copo 1L\";s:4:\"slug\";s:17:\"mocaccino-copo-1l\";s:5:\"preco\";s:5:\"15.00\";s:10:\"quantidade\";i:1;s:7:\"tamanho\";s:7:\"Copo 1L\";}}', '15.00', '5.00', '20.00', 'São José - Itabirito - Rua Francisco José de Carvalho - CEP -35457-190 - MG - Número 29c', 'Ponto de referência: Loja Conexao Eletronicos - Número 29c', '2022-11-17 18:02:50', '2022-11-17 18:04:13', NULL),
(16, 5, 1, '35480269', 'Cartão de Débito', 2, 'a:1:{i:0;a:6:{s:2:\"id\";s:1:\"9\";s:4:\"nome\";s:24:\"Crokete de Carne Pequeno\";s:4:\"slug\";s:24:\"crokete-de-carne-pequeno\";s:5:\"preco\";s:4:\"3.50\";s:10:\"quantidade\";i:9;s:7:\"tamanho\";s:7:\"Pequeno\";}}', '31.50', '5.00', '36.50', 'São José - Itabirito - Rua Francisco José de Carvalho - CEP -35457-190 - MG - Número 29c', 'Ponto de referência: Loja Conexao Eletronicos - Número 29c', '2022-11-17 18:03:25', '2022-11-17 18:04:03', NULL),
(17, 5, 1, '09758431', 'Cartão de crédito', 2, 'a:1:{i:0;a:6:{s:2:\"id\";s:1:\"4\";s:4:\"nome\";s:24:\"Café Pingado Copo 500ml\";s:4:\"slug\";s:23:\"cafe-pingado-copo-500ml\";s:5:\"preco\";s:4:\"8.00\";s:10:\"quantidade\";i:2;s:7:\"tamanho\";s:10:\"Copo 500ml\";}}', '16.00', '5.00', '21.00', 'São José - Itabirito - Rua Francisco José de Carvalho - CEP -35457-190 - MG - Número 29c', 'Ponto de referência: Loja Conexao Eletronicos - Número 29c', '2022-11-17 18:21:21', '2022-11-17 18:22:51', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos_produtos`
--

CREATE TABLE `pedidos_produtos` (
  `id` int(5) UNSIGNED NOT NULL,
  `pedido_id` int(5) UNSIGNED NOT NULL,
  `produto` varchar(128) NOT NULL,
  `quantidade` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pedidos_produtos`
--

INSERT INTO `pedidos_produtos` (`id`, `pedido_id`, `produto`, `quantidade`) VALUES
(3, 16, 'Crokete de Carne Pequeno', 9),
(4, 15, 'Mocaccino Copo 1L', 1),
(5, 17, 'Café Pingado Copo 500ml', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(5) UNSIGNED NOT NULL,
  `categoria_id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `ingredientes` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `imagem` varchar(200) NOT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos`
--

INSERT INTO `produtos` (`id`, `categoria_id`, `nome`, `slug`, `ingredientes`, `ativo`, `imagem`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(3, 126, 'Café Expresso', 'cafe-expresso', 'O mais tradicional de todos é o café espresso. Nessa receita tradicional, o café é servido puro e preparado sob pressão, sem adição de leite ou qualquer outro ingrediente. Em sua forma correta de preparo, a bebida apresenta duas camadas: o café e o seu creme – espuma que tem a função de criar uma barreira que ajuda a preservar o aroma e também a temperatura, além de incorporar o corpo e o retrogosto da bebida.', 1, '1667950293_441acba11a1db220a070.png', '2022-11-08 20:31:23', '2022-11-10 20:53:35', NULL),
(4, 126, 'Café Pingado', 'cafe-pingado', 'O pingado é o tradicional café com leite dos brasileiros. Muito popular nos bares e padarias, essa mistura traz uma quantidade maior de leite e uma pequena dose de café – ou seja, um pingo da bebida. Bastante similar ao pingado está o caffè latte, uma mistura de leite com café espresso, mais uma fina camada de espuma láctea. É quase que um pingado, mas com a adição da espuma do leite.', 1, '1667950548_706823d2c214f6e14a20.png', '2022-11-08 20:33:45', '2022-11-08 20:35:48', NULL),
(5, 127, 'Mocaccino', 'mocaccino', 'O mocha ou mocaccino é para os fãs da combinação café + chocolate. Leva um terço de leite vaporizado, um terço de espuma de leite e outro terço de espresso. E pra deixar tudo melhor, ainda recebe uma calda de chocolate. Pode ser finalizada desse modo mais simples ou, ainda, com a adição de chantilly, pó de cacau ou canela.', 1, '1667950796_2f3eca0139f26a5ebe86.png', '2022-11-08 20:39:50', '2022-11-10 20:53:07', NULL),
(6, 127, 'Cappuccino', 'cappuccino', 'O cappuccino é uma deliciosa invenção italiana, que mistura partes iguais de café espresso e leite vaporizado, resultando em uma bebida cremosa e bem consistente. Pode ser servido com um pouco de chocolate em pó, canela e açúcar.', 1, '1667950861_b58faccbb4720a04aa6e.png', '2022-11-08 20:40:55', '2022-11-10 20:52:49', NULL),
(7, 128, 'Bolinha de Queijo', 'bolinha-de-queijo', 'Bolinha de Queijo', 1, '1667951436_ba77176c71296340654f.jpg', '2022-11-08 20:45:11', '2022-11-08 20:50:36', NULL),
(8, 128, 'Coxinha de Frango', 'coxinha-de-frango', 'Coxinha de Frango', 1, '1667951498_d12023d5f92ffd6ea595.jpg', '2022-11-08 20:51:31', '2022-11-08 20:51:38', NULL),
(9, 128, 'Crokete de Carne', 'crokete-de-carne', 'Crokete de Carne', 1, '1667951564_f12f0018838c026c89ba.jpg', '2022-11-08 20:52:39', '2022-11-08 20:52:44', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos_especificacoes`
--

CREATE TABLE `produtos_especificacoes` (
  `id` int(5) UNSIGNED NOT NULL,
  `produto_id` int(5) UNSIGNED NOT NULL,
  `medida_id` int(5) UNSIGNED NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `customizavel` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `produtos_especificacoes`
--

INSERT INTO `produtos_especificacoes` (`id`, `produto_id`, `medida_id`, `preco`, `customizavel`) VALUES
(9, 3, 6, '4.00', 0),
(10, 3, 5, '6.00', 0),
(11, 3, 7, '8.00', 0),
(12, 4, 6, '6.00', 0),
(13, 4, 5, '8.00', 0),
(14, 4, 7, '12.00', 0),
(15, 5, 6, '8.00', 0),
(16, 5, 5, '12.00', 0),
(17, 5, 7, '15.00', 0),
(18, 6, 6, '7.00', 0),
(19, 6, 5, '11.00', 0),
(20, 6, 7, '14.00', 0),
(21, 7, 8, '4.00', 0),
(22, 7, 9, '2.50', 0),
(23, 8, 9, '3.00', 0),
(24, 8, 8, '4.50', 0),
(25, 9, 9, '3.50', 0),
(26, 9, 8, '5.00', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) UNSIGNED NOT NULL,
  `nome` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `ativacao_hash` varchar(64) DEFAULT NULL,
  `reset_hash` varchar(64) DEFAULT NULL,
  `reset_expira_em` datetime DEFAULT NULL,
  `criado_em` datetime DEFAULT NULL,
  `atualizado_em` datetime DEFAULT NULL,
  `deletado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `cpf`, `telefone`, `is_admin`, `ativo`, `password_hash`, `ativacao_hash`, `reset_hash`, `reset_expira_em`, `criado_em`, `atualizado_em`, `deletado_em`) VALUES
(1, 'Administrador', 'admin@admin.com', '177.032.200-00', '(88) 88888-8888', 0, 1, '$2y$10$Ae0xRVEdv0CPv.fCDupAXO.M3LcX9cWV8R6FOr2.9wgkv//DU2sC6', NULL, '1258f4afa9f5037338b5166bd28a78136bed78c9a158f67130e1e14be1539303', '2022-11-05 16:36:33', '2022-10-12 17:17:07', '2022-11-16 18:15:43', NULL),
(5, 'Daniel', 'guitoflash@gmail.com', '723.381.660-35', '(39) 21093-1290', 1, 1, '$2y$10$SJAn6tzSJ7crM.whhdHsz.fOj/hnpoiBI3XfqoA0jPLxpAhSHQ8zW', NULL, NULL, NULL, '2022-10-19 23:29:51', '2022-11-17 10:45:57', NULL),
(24, 'Email Temp', 'kemef31663@invodua.com', '142.578.816-58', '', 0, 1, '$2y$10$5yJvhX/FH7QlifGXmLQq.OhzMfOQ3Rw9rYm9WCbD7DpaTsLnq2BhO', NULL, NULL, NULL, '2022-11-17 18:47:14', '2022-11-17 18:48:27', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `bairros`
--
ALTER TABLE `bairros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `entregadores`
--
ALTER TABLE `entregadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `cnh` (`cnh`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`);

--
-- Índices para tabela `expediente`
--
ALTER TABLE `expediente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_usuario_id_foreign` (`usuario_id`),
  ADD KEY `pedidos_entregador_id_foreign` (`entregador_id`);

--
-- Índices para tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedidos_produtos_pedido_id_foreign` (`pedido_id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `produtos_categoria_id_foreign` (`categoria_id`);

--
-- Índices para tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produtos_especificacoes_produto_id_foreign` (`produto_id`),
  ADD KEY `produtos_especificacoes_medida_id_foreign` (`medida_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `ativacao_hash` (`ativacao_hash`),
  ADD UNIQUE KEY `reset_hash` (`reset_hash`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bairros`
--
ALTER TABLE `bairros`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de tabela `entregadores`
--
ALTER TABLE `entregadores`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `expediente`
--
ALTER TABLE `expediente`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `formas_pagamento`
--
ALTER TABLE `formas_pagamento`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `medidas`
--
ALTER TABLE `medidas`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_entregador_id_foreign` FOREIGN KEY (`entregador_id`) REFERENCES `entregadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pedidos_produtos`
--
ALTER TABLE `pedidos_produtos`
  ADD CONSTRAINT `pedidos_produtos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Limitadores para a tabela `produtos_especificacoes`
--
ALTER TABLE `produtos_especificacoes`
  ADD CONSTRAINT `produtos_especificacoes_medida_id_foreign` FOREIGN KEY (`medida_id`) REFERENCES `medidas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produtos_especificacoes_produto_id_foreign` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
