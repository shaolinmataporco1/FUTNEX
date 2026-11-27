CREATE DATABASE IF NOT EXISTS futnex_site
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE futnex_site;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  nivel ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS times (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL UNIQUE,
  cidade VARCHAR(100) DEFAULT NULL,
  estado CHAR(2) DEFAULT NULL,
  fundacao SMALLINT UNSIGNED DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jogadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  numero TINYINT UNSIGNED DEFAULT NULL,
  nacionalidade VARCHAR(80) DEFAULT NULL,
  posicao VARCHAR(80) DEFAULT NULL,
  time_id INT DEFAULT NULL,
  CONSTRAINT fk_site_jogador_time FOREIGN KEY (time_id)
    REFERENCES times(id) ON DELETE SET NULL
) ENGINE=InnoDB;

SET @existe_valor_mercado = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='futnex_site' AND TABLE_NAME='jogadores' AND COLUMN_NAME='valor_mercado'
);
SET @sql_valor_mercado = IF(
  @existe_valor_mercado=0,
  'ALTER TABLE jogadores ADD COLUMN valor_mercado DECIMAL(15,2) DEFAULT NULL',
  'SELECT 1'
);
PREPARE stmt_valor_mercado FROM @sql_valor_mercado;
EXECUTE stmt_valor_mercado;
DEALLOCATE PREPARE stmt_valor_mercado;

CREATE TABLE IF NOT EXISTS jogador_carreira (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jogador_id INT NOT NULL,
  time_id INT NOT NULL,
  ano_inicio SMALLINT UNSIGNED DEFAULT NULL,
  ano_fim SMALLINT UNSIGNED DEFAULT NULL,
  INDEX idx_jogador_carreira_jogador (jogador_id, ano_inicio),
  INDEX idx_jogador_carreira_time (time_id),
  CONSTRAINT fk_carreira_jogador FOREIGN KEY (jogador_id)
    REFERENCES jogadores(id) ON DELETE CASCADE,
  CONSTRAINT fk_carreira_time FOREIGN KEY (time_id)
    REFERENCES times(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS noticias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria VARCHAR(60) NOT NULL,
  titulo VARCHAR(180) NOT NULL,
  resumo VARCHAR(500) NOT NULL,
  conteudo TEXT NOT NULL,
  imagem VARCHAR(500) DEFAULT NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT IGNORE INTO times (nome,cidade,estado,fundacao) VALUES
('Flamengo','Rio de Janeiro','RJ',1895),
('Palmeiras',CONVERT(0x53C3A36F205061756C6F USING utf8mb4),'SP',1914),
('Corinthians',CONVERT(0x53C3A36F205061756C6F USING utf8mb4),'SP',1910),
(CONVERT(0x4772C3AA6D696F USING utf8mb4),'Porto Alegre','RS',1903);

INSERT IGNORE INTO times (nome,cidade,estado,fundacao) VALUES
('Santos FC','Santos','SP',1912),
('São Paulo','São Paulo','SP',1930),
('Botafogo','Rio de Janeiro','RJ',1904),
('Fluminense','Rio de Janeiro','RJ',1902),
('Vasco da Gama','Rio de Janeiro','RJ',1898),
('Atlético Mineiro','Belo Horizonte','MG',1908),
('Cruzeiro','Belo Horizonte','MG',1921),
('Bahia','Salvador','BA',1931),
('Fortaleza','Fortaleza','CE',1918),
('Ceará','Fortaleza','CE',1914),
('Sport Recife','Recife','PE',1905),
('Athletico Paranaense','Curitiba','PR',1924),
('Internacional','Porto Alegre','RS',1909),
('Vitória','Salvador','BA',1899),
('Goiás','Goiânia','GO',1943),
('Remo','Belém','PA',1905),
('Paysandu','Belém','PA',1914),
('Cuiabá','Cuiabá','MT',2001),
('América Mineiro','Belo Horizonte','MG',1912),
('Chapecoense','Chapecó','SC',1973);

UPDATE times SET nome='Vasco da Gama' WHERE nome='Vasco da Grama';

INSERT IGNORE INTO times (nome,cidade,estado,fundacao) VALUES
('Barcelona','Barcelona','ES',1899),
('Paris Saint-Germain','Paris','FR',1970),
('Inter Miami','Fort Lauderdale','US',2018),
('Real Madrid','Madrid','ES',1902),
('Chelsea','London','GB',1905),
('Sporting CP','Lisbon','PT',1906),
('Manchester United','Manchester','GB',1878),
('Juventus','Turin','IT',1897),
('Al Nassr','Riyadh','SA',1955),
('Monaco','Monaco','MC',1924),
('RB Salzburg','Salzburg','AT',1933),
('Borussia Dortmund','Dortmund','DE',1909),
('Manchester City','Manchester','GB',1880),
('Fiorentina','Florence','IT',1926),
('Roma','Rome','IT',1927),
('Liverpool','Liverpool','GB',1892),
('Tottenham Hotspur','London','GB',1882),
('Bayern Munich','Munich','DE',1900),
('Leeds United','Leeds','GB',1919),
('Rennes','Rennes','FR',1901),
('Vitória SC','Guimarães','PT',1922),
('Avaí','Florianópolis','SC',1923),
('Al-Hilal','Riyadh','SA',1957),
('Napoli','Naples','IT',1926),
('Birmingham City','Birmingham','GB',1875),
('FC Basel','Basel','CH',1893),
('Werder Bremen','Bremen','DE',1899),
('Wolfsburg','Wolfsburg','DE',1945);

INSERT INTO jogadores (numero,nome,nacionalidade,posicao,time_id)
SELECT 10,'Neymar Jr.','Brasileira','Meia-atacante',NULL
WHERE NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Neymar Jr.');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,time_id)
SELECT 11,'Raphinha','Brasileira','Atacante',NULL
WHERE NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Raphinha');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,time_id)
SELECT 7,CONVERT(0x56696EC3AD63697573204AC3BA6E696F72 USING utf8mb4),'Brasileira','Atacante',NULL
WHERE NOT EXISTS (SELECT 1 FROM jogadores WHERE nome=CONVERT(0x56696EC3AD63697573204AC3BA6E696F72 USING utf8mb4) COLLATE utf8mb4_unicode_ci);

INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 41,'Estêvão','Brasileira','Atacante',500000000,t.id FROM times t
WHERE t.nome='Chelsea' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Estêvão');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 10,'Lionel Messi','Argentina','Atacante',80000000,t.id FROM times t
WHERE t.nome='Inter Miami' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Lionel Messi');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 7,'Cristiano Ronaldo','Portugal','Atacante',50000000,t.id FROM times t
WHERE t.nome='Al Nassr' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Cristiano Ronaldo');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 10,'Kylian Mbappé','França','Atacante',1000000000,t.id FROM times t
WHERE t.nome='Real Madrid' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Kylian Mbappé');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 9,'Erling Haaland','Noruega','Atacante',1000000000,t.id FROM times t
WHERE t.nome='Manchester City' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Erling Haaland');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 11,'Mohamed Salah','Egito','Atacante',250000000,t.id FROM times t
WHERE t.nome='Liverpool' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Mohamed Salah');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 5,'Jude Bellingham','Inglaterra','Meio-campista',900000000,t.id FROM times t
WHERE t.nome='Real Madrid' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Jude Bellingham');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 11,'Rodrygo','Brasileira','Atacante',600000000,t.id FROM times t
WHERE t.nome='Real Madrid' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Rodrygo');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 17,'Kevin De Bruyne','Bélgica','Meio-campista',150000000,t.id FROM times t
WHERE t.nome='Napoli' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Kevin De Bruyne');
INSERT INTO jogadores (numero,nome,nacionalidade,posicao,valor_mercado,time_id)
SELECT 9,'Harry Kane','Inglaterra','Atacante',300000000,t.id FROM times t
WHERE t.nome='Bayern Munich' AND NOT EXISTS (SELECT 1 FROM jogadores WHERE nome='Harry Kane');

UPDATE jogadores j JOIN times t ON t.nome='Santos FC'
SET j.valor_mercado=80000000,j.time_id=t.id
WHERE j.nome='Neymar Jr.' AND j.valor_mercado IS NULL;
UPDATE jogadores j JOIN times t ON t.nome='Barcelona'
SET j.valor_mercado=600000000,j.time_id=t.id
WHERE j.nome='Raphinha' AND j.valor_mercado IS NULL;
UPDATE jogadores j JOIN times t ON t.nome='Real Madrid'
SET j.valor_mercado=1000000000,j.time_id=t.id
WHERE j.nome=CONVERT(0x56696EC3AD63697573204AC3BA6E696F72 USING utf8mb4) COLLATE utf8mb4_unicode_ci
  AND j.valor_mercado IS NULL;

INSERT INTO jogador_carreira (jogador_id,time_id,ano_inicio,ano_fim)
SELECT j.id,t.id,c.inicio,c.fim
FROM (
  SELECT 'Neymar Jr.' AS jogador,'Santos FC' AS clube,2009 AS inicio,2013 AS fim
  UNION ALL SELECT 'Neymar Jr.','Barcelona',2013,2017
  UNION ALL SELECT 'Neymar Jr.','Paris Saint-Germain',2017,2023
  UNION ALL SELECT 'Neymar Jr.','Al-Hilal',2023,2025
  UNION ALL SELECT 'Neymar Jr.','Santos FC',2025,NULL
  UNION ALL SELECT 'Raphinha','Avaí',2014,2016
  UNION ALL SELECT 'Raphinha','Vitória SC',2016,2018
  UNION ALL SELECT 'Raphinha','Sporting CP',2018,2019
  UNION ALL SELECT 'Raphinha','Rennes',2019,2020
  UNION ALL SELECT 'Raphinha','Leeds United',2020,2022
  UNION ALL SELECT 'Raphinha','Barcelona',2022,NULL
  UNION ALL SELECT 'Vinícius Júnior','Flamengo',2017,2018
  UNION ALL SELECT 'Vinícius Júnior','Real Madrid',2018,NULL
  UNION ALL SELECT 'Estêvão','Palmeiras',2023,2025
  UNION ALL SELECT 'Estêvão','Chelsea',2025,NULL
  UNION ALL SELECT 'Lionel Messi','Barcelona',2004,2021
  UNION ALL SELECT 'Lionel Messi','Paris Saint-Germain',2021,2023
  UNION ALL SELECT 'Lionel Messi','Inter Miami',2023,NULL
  UNION ALL SELECT 'Cristiano Ronaldo','Sporting CP',2002,2003
  UNION ALL SELECT 'Cristiano Ronaldo','Manchester United',2003,2009
  UNION ALL SELECT 'Cristiano Ronaldo','Real Madrid',2009,2018
  UNION ALL SELECT 'Cristiano Ronaldo','Juventus',2018,2021
  UNION ALL SELECT 'Cristiano Ronaldo','Manchester United',2021,2022
  UNION ALL SELECT 'Cristiano Ronaldo','Al Nassr',2023,NULL
  UNION ALL SELECT 'Kylian Mbappé','Monaco',2015,2018
  UNION ALL SELECT 'Kylian Mbappé','Paris Saint-Germain',2017,2024
  UNION ALL SELECT 'Kylian Mbappé','Real Madrid',2024,NULL
  UNION ALL SELECT 'Erling Haaland','RB Salzburg',2019,2020
  UNION ALL SELECT 'Erling Haaland','Borussia Dortmund',2020,2022
  UNION ALL SELECT 'Erling Haaland','Manchester City',2022,NULL
  UNION ALL SELECT 'Mohamed Salah','FC Basel',2012,2014
  UNION ALL SELECT 'Mohamed Salah','Chelsea',2014,2016
  UNION ALL SELECT 'Mohamed Salah','Fiorentina',2015,2015
  UNION ALL SELECT 'Mohamed Salah','Roma',2015,2017
  UNION ALL SELECT 'Mohamed Salah','Liverpool',2017,NULL
  UNION ALL SELECT 'Jude Bellingham','Birmingham City',2019,2020
  UNION ALL SELECT 'Jude Bellingham','Borussia Dortmund',2020,2023
  UNION ALL SELECT 'Jude Bellingham','Real Madrid',2023,NULL
  UNION ALL SELECT 'Rodrygo','Santos FC',2017,2019
  UNION ALL SELECT 'Rodrygo','Real Madrid',2019,NULL
  UNION ALL SELECT 'Kevin De Bruyne','Werder Bremen',2012,2013
  UNION ALL SELECT 'Kevin De Bruyne','Chelsea',2013,2014
  UNION ALL SELECT 'Kevin De Bruyne','Wolfsburg',2014,2015
  UNION ALL SELECT 'Kevin De Bruyne','Manchester City',2015,2025
  UNION ALL SELECT 'Kevin De Bruyne','Napoli',2025,NULL
  UNION ALL SELECT 'Harry Kane','Tottenham Hotspur',2011,2023
  UNION ALL SELECT 'Harry Kane','Bayern Munich',2023,NULL
) AS c
INNER JOIN jogadores j ON j.nome=c.jogador
INNER JOIN times t ON t.nome=c.clube
WHERE NOT EXISTS (
  SELECT 1 FROM jogador_carreira jc
  WHERE jc.jogador_id=j.id AND jc.time_id=t.id AND jc.ano_inicio=c.inicio
);

CREATE TEMPORARY TABLE IF NOT EXISTS futnex_seed_carreira (
  jogador VARCHAR(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  clube VARCHAR(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (jogador,clube)
) ENGINE=InnoDB;
DELETE FROM futnex_seed_carreira;

INSERT IGNORE INTO futnex_seed_carreira (jogador,clube) VALUES
('Zico','Flamengo'),
('Júnior','Flamengo'),
('Ademir da Guia','Palmeiras'),
('Marcos','Palmeiras'),
('Sócrates','Corinthians'),
('Cássio','Corinthians'),
('Marcelinho Carioca','Corinthians'),
('Renato Portaluppi','Grêmio'),
('Ronaldinho','Grêmio'),
('Paulo Nunes','Grêmio'),
('Pelé','Santos FC'),
('Rogério Ceni','São Paulo'),
('Kaká','São Paulo'),
('Raí','São Paulo'),
('Garrincha','Botafogo'),
('Nilton Santos','Botafogo'),
('Jairzinho','Botafogo'),
('Fred','Fluminense'),
('Marcelo','Fluminense'),
('Thiago Silva','Fluminense'),
('Roberto Dinamite','Vasco da Gama'),
('Edmundo','Vasco da Gama'),
('Juninho Pernambucano','Vasco da Gama'),
('Hulk','Atlético Mineiro'),
('Ronaldinho','Atlético Mineiro'),
('Reinaldo','Atlético Mineiro'),
('Tostão','Cruzeiro'),
('Alex','Cruzeiro'),
('Fábio','Cruzeiro'),
('Bobô','Bahia'),
('Beijoca','Bahia'),
('Daniel Alves','Bahia'),
('Tinga','Fortaleza'),
('Yago Pikachu','Fortaleza'),
('Lucero','Fortaleza'),
('Magno Alves','Ceará'),
('Gildo','Ceará'),
('Vina','Ceará'),
('Diego Souza','Sport Recife'),
('Magrão','Sport Recife'),
('Durval','Sport Recife'),
('Bruno Guimarães','Athletico Paranaense'),
('Fernandinho','Athletico Paranaense'),
('Alex Mineiro','Athletico Paranaense'),
('Falcão','Internacional'),
('Fernandão','Internacional'),
('D''Alessandro','Internacional'),
('Dinei','Vitória'),
('Ramon Menezes','Vitória'),
('Vampeta','Vitória'),
('Fernandão','Goiás'),
('Walter','Goiás'),
('Rafael Moura','Goiás'),
('Alcino','Remo'),
('Bira','Remo'),
('Biro-Biro','Remo'),
('Cacaio','Paysandu'),
('Dadinho','Paysandu'),
('Vandick','Paysandu'),
('Deyverson','Cuiabá'),
('Clayson','Cuiabá'),
('Alan Empereur','Cuiabá'),
('Richarlison','América Mineiro'),
('Matheusinho','América Mineiro'),
('Juninho','América Mineiro'),
('Alan Ruschel','Chapecoense'),
('Neto','Chapecoense'),
('Jakson Follmann','Chapecoense'),
('Luis Suárez','Inter Miami'),
('Sergio Busquets','Inter Miami'),
('Bruno Fernandes','Sporting CP'),
('Wayne Rooney','Manchester United'),
('Ryan Giggs','Manchester United'),
('Alessandro Del Piero','Juventus'),
('Gianluigi Buffon','Juventus'),
('Sadio Mané','Al Nassr'),
('Anderson Talisca','Al Nassr'),
('Thierry Henry','Monaco'),
('Radamel Falcao','Monaco'),
('Sadio Mané','RB Salzburg'),
('Dominik Szoboszlai','RB Salzburg'),
('Marco Reus','Borussia Dortmund'),
('Sergio Agüero','Manchester City'),
('Gabriel Batistuta','Fiorentina'),
('Luca Toni','Fiorentina'),
('Francesco Totti','Roma'),
('Daniele De Rossi','Roma'),
('Steven Gerrard','Liverpool'),
('Kenny Dalglish','Liverpool'),
('Son Heung-min','Tottenham Hotspur'),
('Gareth Bale','Tottenham Hotspur'),
('Manuel Neuer','Bayern Munich'),
('Thomas Müller','Bayern Munich'),
('Harry Kewell','Leeds United'),
('Mark Viduka','Leeds United'),
('Eduardo Camavinga','Rennes'),
('Ousmane Dembélé','Rennes'),
('André André','Vitória SC'),
('Ricardo Pereira','Vitória SC'),
('Marquinhos Santos','Avaí'),
('Evando','Avaí'),
('Salem Al-Dawsari','Al-Hilal'),
('Yasser Al-Qahtani','Al-Hilal'),
('Diego Maradona','Napoli'),
('Marek Hamšík','Napoli'),
('Trevor Francis','Birmingham City'),
('Christophe Dugarry','Birmingham City'),
('Xherdan Shaqiri','FC Basel'),
('Yann Sommer','FC Basel'),
('Miroslav Klose','Werder Bremen'),
('Mesut Özil','Werder Bremen'),
('Edin Džeko','Wolfsburg'),
('Grafite','Wolfsburg');

INSERT INTO jogadores (nome)
SELECT DISTINCT c.jogador
FROM futnex_seed_carreira c
WHERE NOT EXISTS (
  SELECT 1 FROM jogadores j WHERE j.nome=c.jogador
);

INSERT INTO jogador_carreira (jogador_id,time_id)
SELECT j.id,t.id
FROM futnex_seed_carreira c
INNER JOIN jogadores j ON j.nome=c.jogador
INNER JOIN times t ON t.nome=c.clube
WHERE NOT EXISTS (
  SELECT 1 FROM jogador_carreira jc
  WHERE jc.jogador_id=j.id AND jc.time_id=t.id
    AND jc.ano_inicio IS NULL AND jc.ano_fim IS NULL
);

DROP TEMPORARY TABLE futnex_seed_carreira;

INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT CONVERT(0x42726173696C656972C3A36F USING utf8mb4),'Uma rodada decisiva agita o campeonato',
 CONVERT(0x54696D657320747261646963696F6E61697320656E7472616D20656D2063616D706F20656D20627573636120646520706F7369C3A7C3B5657320696D706F7274616E746573206E6120746162656C612E USING utf8mb4),
 CONVERT(0x4120726F646164612070726F6D65746520666F7274657320656D6FC3A7C3B56573206520636F6E66726F6E746F7320696D706F7274616E7465732070617261206120636C6173736966696361C3A7C3A36F2E USING utf8mb4),
 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Uma rodada decisiva agita o campeonato');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT CONVERT(0x53656C65C3A7C3A36F USING utf8mb4),'Estrelas brasileiras dão show pelo mundo',
 'Os principais nomes do futebol brasileiro seguem brilhando nas ligas internacionais.',
 'Jogadores brasileiros continuam em destaque nos principais campeonatos do mundo.',
 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Estrelas brasileiras dão show pelo mundo');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Mercado','Janela movimenta os grandes clubes',
 CONVERT(0x4469726967656E746573206163656C6572616D206E65676F636961C3A7C3B56573207061726120666F7274616C65636572206F7320656C656E636F73206E61207072C3B378696D6120666173652E USING utf8mb4),
 CONVERT(0x4F7320636C75626573206176616C69616D207265666F72C3A76F7320652074726162616C68616D207061726120666563686172206E6F76617320636F6E7472617461C3A7C3B565732E USING utf8mb4),
 'https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Janela movimenta os grandes clubes');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Copa do Brasil','Copa do Brasil: tradição e mata-mata',
 'Acompanhe os clubes, confrontos e histórias de uma das principais copas nacionais.',
 'Nesta seção reunimos informações e notícias sobre a Copa do Brasil. Confira os conteúdos e acompanhe a competição.',
 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Copa do Brasil: tradição e mata-mata');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Copa Libertadores','Libertadores: a disputa continental em foco',
 'Notícias, clubes e curiosidades da principal competição de clubes da América do Sul.',
 'Acompanhe a cobertura da Copa Libertadores e conheça os destaques da disputa continental.',
 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Libertadores: a disputa continental em foco');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Copa Sul-Americana','Sul-Americana: clubes em busca do título',
 'Veja conteúdos e informações sobre os times que disputam o torneio sul-americano.',
 'Esta página reúne a cobertura da Copa Sul-Americana, com notícias e informações sobre a competição.',
 'https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Sul-Americana: clubes em busca do título');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Champions League','Champions League: os grandes duelos europeus',
 'Acompanhe notícias e histórias da competição entre os principais clubes da Europa.',
 'Confira a cobertura da Champions League, seus clubes participantes e os principais assuntos do torneio.',
 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Champions League: os grandes duelos europeus');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Premier League','Premier League: futebol inglês em destaque',
 'Informações e notícias sobre os clubes e a temporada do campeonato inglês.',
 'Acompanhe conteúdos sobre a Premier League e seus clubes ao longo da temporada.',
 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Premier League: futebol inglês em destaque');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'La Liga','La Liga: acompanhe o campeonato espanhol',
 'Notícias e informações dos clubes que disputam a liga espanhola.',
 'Confira a cobertura de La Liga e acompanhe os principais temas do campeonato espanhol.',
 'https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='La Liga: acompanhe o campeonato espanhol');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Bundesliga','Bundesliga: tradição e intensidade na Alemanha',
 'Acompanhe os clubes e as notícias do campeonato alemão.',
 'Esta seção reúne informações e notícias sobre a Bundesliga e seus clubes.',
 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Bundesliga: tradição e intensidade na Alemanha');
INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem)
SELECT 'Mundial de Clubes','Mundial de Clubes: campeões de diferentes continentes',
 'Saiba mais sobre o torneio que reúne clubes de diversas partes do mundo.',
 'Acompanhe notícias e informações sobre o Mundial de Clubes e seus representantes.',
 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=900&q=80'
WHERE NOT EXISTS (SELECT 1 FROM noticias WHERE titulo='Mundial de Clubes: campeões de diferentes continentes');
