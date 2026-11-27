<?php
require __DIR__ . '/config.php';
$pagina = 'Times';
require __DIR__ . '/partials/header.php';

$idolosETrofeus = [
  'Flamengo' => [
    'idolos' => ['Zico', 'Júnior', 'Gabigol'],
    'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 4], ['nome' => 'Copa do Brasil', 'quantidade' => 5], ['nome' => 'Brasileirão', 'quantidade' => 8], ['nome' => 'Mundial de Clubes', 'quantidade' => 1]],
    'historia' => 'Fundado em 1895, o Flamengo virou uma das maiores potências do futebol brasileiro. O clube marcou gerações com Zico nos anos 1980 e voltou ao topo com conquistas continentais recentes.',
  ],
  'Palmeiras' => ['idolos' => ['Ademir da Guia', 'Evair', 'Marcos'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 3], ['nome' => 'Copa do Brasil', 'quantidade' => 4], ['nome' => 'Brasileirão', 'quantidade' => 12]], 'historia' => 'Criado em 1914 por integrantes da comunidade italiana, o Palmeiras construiu uma das maiores histórias do futebol brasileiro, com gerações marcantes como a Academia e a era moderna dos títulos.'],
  'Corinthians' => ['idolos' => ['Sócrates', 'Rivelino', 'Cássio'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 1], ['nome' => 'Mundial de Clubes', 'quantidade' => 2], ['nome' => 'Brasileirão', 'quantidade' => 7], ['nome' => 'Copa do Brasil', 'quantidade' => 4]], 'historia' => 'Fundado em 1910 por trabalhadores de São Paulo, o Corinthians se tornou símbolo de identidade popular e paixão. Seus títulos internacionais em 2000 e 2012 reforçaram sua tradição nacional e mundial.'],
  'Santos FC' => ['idolos' => ['Pelé', 'Robinho', 'Neymar Jr.'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 3], ['nome' => 'Mundial Interclubes', 'quantidade' => 2], ['nome' => 'Brasileirão', 'quantidade' => 8], ['nome' => 'Copa do Brasil', 'quantidade' => 1]], 'historia' => 'O Santos foi fundado em 1912 e alcançou fama mundial na Era Pelé, conduzindo um futebol ofensivo que marcou os anos 1960. Continuou revelando grandes jogadores nas décadas seguintes.'],
  'São Paulo' => ['idolos' => ['Rogério Ceni', 'Raí', 'Leonardo'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 3], ['nome' => 'Mundial de Clubes', 'quantidade' => 3], ['nome' => 'Brasileirão', 'quantidade' => 6], ['nome' => 'Copa do Brasil', 'quantidade' => 1]], 'historia' => 'Fundado em 1930, o São Paulo venceu períodos decisivos no Morumbi e se destacou internacionalmente nos anos 1990 e 2005, com equipes formadas por grandes nomes.'],
  'Grêmio' => ['idolos' => ['Renato Portaluppi', 'Geromel', 'Alcindo'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 3], ['nome' => 'Copa do Brasil', 'quantidade' => 5], ['nome' => 'Brasileirão', 'quantidade' => 2], ['nome' => 'Mundial de Clubes', 'quantidade' => 1]], 'historia' => 'Criado em 1903 em Porto Alegre, o Grêmio se consolidou com temporadas históricas e equipes de forte garra. Seus títulos internacionais marcaram especialmente 1983 e 2017.'],
  'Internacional' => ['idolos' => ['Falcão', 'Fernandão', 'D\'Alessandro'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 2], ['nome' => 'Mundial de Clubes', 'quantidade' => 1], ['nome' => 'Brasileirão', 'quantidade' => 3], ['nome' => 'Copa do Brasil', 'quantidade' => 3]], 'historia' => 'Fundado em 1909, o Internacional ganhou identidade em Porto Alegre e viveu conquistas marcantes em 1975, 2006 e 2010, sempre ligado a uma grande rivalidade local e nacional.'],
  'Atlético Mineiro' => ['idolos' => ['Reinaldo', 'Ronaldinho', 'Tardelli'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 1], ['nome' => 'Copa do Brasil', 'quantidade' => 2], ['nome' => 'Brasileirão', 'quantidade' => 3]], 'historia' => 'O Atlético Mineiro foi fundado em Belo Horizonte em 1908 e tornou-se símbolo de grandeza mineira. A conquista da Libertadores de 2013 marcou um dos capítulos mais celebrados pelo clube.'],
  'Cruzeiro' => ['idolos' => ['Fábio', 'Tostão', 'Dirceu Lopes'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 2], ['nome' => 'Copa do Brasil', 'quantidade' => 6], ['nome' => 'Brasileirão', 'quantidade' => 4]], 'historia' => 'Fundado em 1921, o Cruzeiro ganhou projeção com grandes elencos mineiros e títulos nacionais expressivos. É conhecido por campanhas de Copa do Brasil e pela excelência em sua formação histórica.'],
  'Botafogo' => ['idolos' => ['Nilton Santos', 'Garrincha', 'Jefferson'], 'trofeus' => [['nome' => 'Brasileirão', 'quantidade' => 3], ['nome' => 'Copa Conmebol', 'quantidade' => 1], ['nome' => 'Copa do Brasil', 'quantidade' => 1]], 'historia' => 'O Botafogo surgiu no Rio de Janeiro em 1904 e ficou associado ao talento de Nilton Santos e Garrincha. Ganhou novo protagonismo internacional ao conquistar a Copa Libertadores em 2024.'],
  'Fluminense' => ['idolos' => ['Rivellino', 'Fred', 'Túlio'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 1], ['nome' => 'Brasileirão', 'quantidade' => 4], ['nome' => 'Copa do Brasil', 'quantidade' => 1]], 'historia' => 'Fundado em 1902 no Rio de Janeiro, o Fluminense construiu tradição com Elásticas, conquistas nacionais e forte ligação com o Maracanã. A Libertadores de 2023 abriu uma nova era continental.'],
  'Vasco da Gama' => ['idolos' => ['Roberto Dinamite', 'Romário', 'Edmundo'], 'trofeus' => [['nome' => 'Libertadores', 'quantidade' => 1], ['nome' => 'Copa do Brasil', 'quantidade' => 1], ['nome' => 'Brasileirão', 'quantidade' => 4]], 'historia' => 'O Vasco foi fundado em 1898 e tem destaque histórico na inclusão social do futebol brasileiro. Sua torcida atravessou eras gloriosas lideradas por Roberto Dinamite e Romário.'],
  'Bahia' => ['idolos' => ['Bebeto', 'Bobô', 'Nenzinho'], 'trofeus' => [['nome' => 'Brasileirão', 'quantidade' => 2], ['nome' => 'Copa do Nordeste', 'quantidade' => 5]], 'historia' => 'Fundado em 1931, o Bahia foi campeão nacional em momentos fundamentais da história do clube. Mantém forte tradição no Nordeste e no estádio da Fonte Nova.'],
  'Athletico Paranaense' => ['idolos' => ['Alex Mineiro', 'Fernandinho', 'Weverton'], 'trofeus' => [['nome' => 'Copa Sul-Americana', 'quantidade' => 2], ['nome' => 'Copa do Brasil', 'quantidade' => 1], ['nome' => 'Brasileirão', 'quantidade' => 1]], 'historia' => 'Criado em 1924 em Curitiba, o Athletico ganhou projeção nacional e internacional com gestão moderna e campanhas expressivas na Arena da Baixada.'],
  'Fortaleza' => ['idolos' => ['Moésio', 'Assis', 'Marcelo Boeck'], 'trofeus' => [['nome' => 'Copa do Nordeste', 'quantidade' => 3], ['nome' => 'Campeonato Cearense', 'quantidade' => 46]], 'historia' => 'Fundado em 1918, o Fortaleza é um dos clubes mais tradicionais do Nordeste. A equipe viveu forte crescimento esportivo nas últimas décadas e ampliou sua presença nacional.'],
  'Sport Recife' => ['idolos' => ['Leonardo', 'Juninho', 'Magrão'], 'trofeus' => [['nome' => 'Brasileirão', 'quantidade' => 1], ['nome' => 'Copa do Brasil', 'quantidade' => 1], ['nome' => 'Copa do Nordeste', 'quantidade' => 3]], 'historia' => 'Fundado em 1905 em Recife, o Sport passou por períodos decisivos na Ilha do Retiro. Conquistas nacionais e nordestinas fortalecem sua tradição regional.'],
  'Ceará' => ['idolos' => ['Gildo', 'Magno Alves', 'Vovô'], 'trofeus' => [['nome' => 'Copa do Nordeste', 'quantidade' => 3], ['nome' => 'Campeonato Cearense', 'quantidade' => 46]], 'historia' => 'O Ceará foi fundado em 1914 e se tornou uma das referências do futebol cearense. A Arena Castelão e o clássico com o Fortaleza fazem parte de sua forte identidade.'],
  'Chapecoense' => ['idolos' => ['Bruninho', 'Danilo', 'Rafael Baca'], 'trofeus' => [['nome' => 'Copa Sul-Americana', 'quantidade' => 1], ['nome' => 'Campeonato Catarinense', 'quantidade' => 7]], 'historia' => 'Fundada em 1973 em Chapecó, a Chapecoense construiu uma ascensão extraordinária até a Série A e tornou-se símbolo de união. A conquista da Copa Sul-Americana de 2016 marcou sua história.'],
  'Barcelona' => ['idolos' => ['Lionel Messi', 'Xavi', 'Iniesta'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 5], ['nome' => 'La Liga', 'quantidade' => 28], ['nome' => 'Copa del Rey', 'quantidade' => 32]], 'historia' => 'Fundado em 1899, o Barcelona é um dos maiores clubes da Espanha e representa a identidade catalã. A era moderna alcançou fama mundial com Lionel Messi e o estilo conhecido como tiki-taka.'],
  'Real Madrid' => ['idolos' => ['Cristiano Ronaldo', 'Raúl', 'Di Stéfano'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 15], ['nome' => 'La Liga', 'quantidade' => 36], ['nome' => 'Copa do Rei', 'quantidade' => 20]], 'historia' => 'O Real Madrid foi fundado em 1902 em Madrid e se tornou o clube mais reconhecido pelo domínio europeu. Construiu grandes gerações em todas as épocas do futebol.'],
  'Paris Saint-Germain' => ['idolos' => ['Zlatan Ibrahimović', 'Thiago Silva', 'Marquinhos'], 'trofeus' => [['nome' => 'Liga 1', 'quantidade' => 13], ['nome' => 'Copa da França', 'quantidade' => 15], ['nome' => 'Liga dos Campeões', 'quantidade' => 1]], 'historia' => 'Fundado em 1970, o PSG se consolidou como principal força francesa nas últimas décadas. Sua ascensão foi marcada por investimentos, estrelas internacionais e conquistas nacionais.'],
  'Inter Miami' => ['idolos' => ['Lionel Messi', 'Busquets', 'Jordi Alba'], 'trofeus' => [['nome' => 'Supporters Shield', 'quantidade' => 1], ['nome' => 'Leagues Cup', 'quantidade' => 1]], 'historia' => 'Fundado em 2018, o Inter Miami levou Lionel Messi ao futebol dos Estados Unidos em 2023. O clube rapidamente ganhou projeção internacional e títulos recentes.'],
  'Chelsea' => ['idolos' => ['Frank Lampard', 'John Terry', 'Didier Drogba'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 2], ['nome' => 'Premier League', 'quantidade' => 6], ['nome' => 'Copa da Inglaterra', 'quantidade' => 8]], 'historia' => 'Fundado em 1905 em Londres, o Chelsea passou por sua maior era de glórias no século XXI. Conquistou a Europa e se tornou uma das forças do futebol inglês.'],
  'Manchester United' => ['idolos' => ['George Best', 'Ryan Giggs', 'Cristiano Ronaldo'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 3], ['nome' => 'Premier League', 'quantidade' => 20], ['nome' => 'Copa da Inglaterra', 'quantidade' => 13]], 'historia' => 'Fundado em 1878, o Manchester United construiu uma tradição histórica em Inglaterra e na Europa. A era de Sir Alex Ferguson tornou o clube uma referência mundial.'],
  'Manchester City' => ['idolos' => ['Sergio Agüero', 'David Silva', 'Kevin De Bruyne'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 1], ['nome' => 'Premier League', 'quantidade' => 10], ['nome' => 'Copa da Inglaterra', 'quantidade' => 7]], 'historia' => 'O Manchester City foi fundado em 1880 e viveu sua maior transformação esportiva no século XXI. Conquistou a Europa e dominou a Premier League com equipes altamente técnicas.'],
  'Liverpool' => ['idolos' => ['Steven Gerrard', 'Kenny Dalglish', 'Mohamed Salah'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 6], ['nome' => 'Premier League', 'quantidade' => 20], ['nome' => 'Copa da Inglaterra', 'quantidade' => 8]], 'historia' => 'Fundado em 1892, o Liverpool é um dos maiores clubes ingleses e europeus. Anfield e seus hinos fizeram do clube símbolo de identidade e tradição continental.'],
  'Bayern Munich' => ['idolos' => ['Franz Beckenbauer', 'Gerd Müller', 'Bastian Schweinsteiger'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 6], ['nome' => 'Bundesliga', 'quantidade' => 34], ['nome' => 'Copa da Alemanha', 'quantidade' => 20]], 'historia' => 'O Bayern München foi fundado em 1900 e domina historicamente o futebol alemão. Sua consistência esportiva também produziu múltiplas conquistas europeias.'],
  'Juventus' => ['idolos' => ['Del Piero', 'Buffon', 'Platini'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 2], ['nome' => 'Serie A', 'quantidade' => 36], ['nome' => 'Copa da Itália', 'quantidade' => 15]], 'historia' => 'Fundada em Turim em 1897, a Juventus é o clube italiano com maior tradição doméstica. Ficou conhecida por solidez, organização e pelo compromisso com a juventude.'],
  'Borussia Dortmund' => ['idolos' => ['Marco Reus', 'Michael Zorc', 'Jürgen Kohler'], 'trofeus' => [['nome' => 'Liga dos Campeões', 'quantidade' => 1], ['nome' => 'Bundesliga', 'quantidade' => 8], ['nome' => 'Copa da Alemanha', 'quantidade' => 5]], 'historia' => 'Fundado em 1909, o Borussia Dortmund tem uma das torcidas mais conhecidas da Europa. O Signal Iduna Park e o Yellow Wall são símbolos de sua identidade.'],
  'Napoli' => ['idolos' => ['Diego Maradona', 'Dries Mertens', 'Marek Hamsik'], 'trofeus' => [['nome' => 'Serie A', 'quantidade' => 4], ['nome' => 'Copa da Itália', 'quantidade' => 6], ['nome' => 'Liga Europa', 'quantidade' => 1]], 'historia' => 'Fundado em 1926, o Napoli é símbolo da cidade de Nápoles. Diego Maradona marcou uma era especial, incluindo o primeiro título italiano do clube.'],
  'Roma' => ['idolos' => ['Francesco Totti', 'Daniele De Rossi', 'Falcão'], 'trofeus' => [['nome' => 'Serie A', 'quantidade' => 3], ['nome' => 'Copa da Itália', 'quantidade' => 9], ['nome' => 'Liga Conferência', 'quantidade' => 1]], 'historia' => 'Fundada em Roma em 1927, a Roma representa a capital italiana em rivalidade com a Lazio. Francesco Totti tornou-se o maior símbolo da história do clube.'],
  'Fiorentina' => ['idolos' => ['Gabriel Batistuta', 'Giancarlo Antognoni', 'Roberto Baggio'], 'trofeus' => [['nome' => 'Serie A', 'quantidade' => 2], ['nome' => 'Copa da Itália', 'quantidade' => 6]], 'historia' => 'Fundada em 1926 em Florença, a Fiorentina combina tradição esportiva e identidade artística. Sua torcida ficou marcada por grandes jogadores e temporadas europeias.'],
  'Tottenham Hotspur' => ['idolos' => ['Jimmy Greaves', 'Harry Kane', 'Glenn Hoddle'], 'trofeus' => [['nome' => 'Liga Europa', 'quantidade' => 3], ['nome' => 'Premier League', 'quantidade' => 2], ['nome' => 'Copa da Inglaterra', 'quantidade' => 8]], 'historia' => 'Fundado em 1882 em Londres, o Tottenham foi pioneiro em diversos momentos do futebol inglês. O clube segue ligado ao estilo ofensivo e à tradição em Copas europeias.'],
  'Leeds United' => ['idolos' => ['Billy Bremner', 'Jack Charlton', 'Don Revie'], 'trofeus' => [['nome' => 'Premier League', 'quantidade' => 3], ['nome' => 'Copa da Inglaterra', 'quantidade' => 1], ['nome' => 'Copa da Liga', 'quantidade' => 1]], 'historia' => 'Fundado em 1919, o Leeds United viveu seus maiores períodos sob Don Revie. O Elland Road e sua torcida mantêm forte identidade histórica.'],
  'Rennes' => ['idolos' => ['Silva', 'Eduardo', 'Bourigeaud'], 'trofeus' => [['nome' => 'Copa da França', 'quantidade' => 3]], 'historia' => 'Fundado em 1901, o Rennes se tornou referência pelo trabalho de formação e desenvolvimento de jovens talentos na Ligue 1.'],
  'Sporting CP' => ['idolos' => ['Cristiano Ronaldo', 'Luis Figo', 'João Mário'], 'trofeus' => [['nome' => 'Liga Portuguesa', 'quantidade' => 20], ['nome' => 'Copa de Portugal', 'quantidade' => 17]], 'historia' => 'Fundado em 1906 em Lisboa, o Sporting tem tradição na formação de atletas e rivalidades históricas em Portugal. É reconhecido mundialmente pela academia de Alcochete.'],
  'Vitória SC' => ['idolos' => ['Costinha', 'Paulinho', 'Pina'], 'trofeus' => [['nome' => 'Copa de Portugal', 'quantidade' => 1], ['nome' => 'Supertaça', 'quantidade' => 1]], 'historia' => 'Fundado em 1922 em Guimarães, o Vitória SC é um dos clubes tradicionais do norte de Portugal e possui forte ligação com a identidade da cidade.'],
  'Al Nassr' => ['idolos' => ['Majed Abdullah', 'Cristiano Ronaldo', 'Marius'], 'trofeus' => [['nome' => 'Liga Saudita', 'quantidade' => 9], ['nome' => 'Copa do Rei', 'quantidade' => 6]], 'historia' => 'Fundado em 1955 em Riad, o Al Nassr é um dos clubes mais tradicionais da Arábia Saudita. Ganhou repercussão mundial com a chegada de Cristiano Ronaldo.'],
  'Al-Hilal' => ['idolos' => ['Mohamed Al-Deayea', 'Yasser Al-Qahtani', 'Salem Al-Dawsari'], 'trofeus' => [['nome' => 'Liga Saudita', 'quantidade' => 19], ['nome' => 'Liga dos Campeões da Ásia', 'quantidade' => 4]], 'historia' => 'O Al-Hilal foi fundado em 1957 e é um dos clubes mais vencedores da Ásia. Conquistou protagonismo doméstico e continental durante várias gerações.'],
  'Monaco' => ['idolos' => ['Thierry Henry', 'Kylian Mbappé', 'Giuly'], 'trofeus' => [['nome' => 'Ligue 1', 'quantidade' => 8], ['nome' => 'Copa da França', 'quantidade' => 5]], 'historia' => 'Fundado em 1924, o Monaco destacou-se na França por revelar talentos e competir com grandes clubes europeus.'],
  'RB Salzburg' => ['idolos' => ['Sadio Mané', 'Erling Haaland', 'Takumi Minamino'], 'trofeus' => [['nome' => 'Bundesliga Austríaca', 'quantidade' => 17], ['nome' => 'Copa da Áustria', 'quantidade' => 9]], 'historia' => 'Fundado em 1933 como Austria Salzburg e reestruturado posteriormente, o clube austríaco ficou conhecido pela formação de jovens estrelas.'],
  'Werder Bremen' => ['idolos' => ['Miroslav Klose', 'Ailton', 'Marco Bode'], 'trofeus' => [['nome' => 'Bundesliga', 'quantidade' => 4], ['nome' => 'Copa da Alemanha', 'quantidade' => 6]], 'historia' => 'O Werder Bremen foi fundado em 1899 e tornou-se uma das forças tradicionais do futebol alemão, especialmente nas décadas de 1980 e 2000.'],
  'Wolfsburg' => ['idolos' => ['Edin Džeko', 'Grafite', 'Kevin De Bruyne'], 'trofeus' => [['nome' => 'Bundesliga', 'quantidade' => 1], ['nome' => 'Copa da Alemanha', 'quantidade' => 1]], 'historia' => 'Fundado em 1945, o Wolfsburg alcançou seu auge esportivo em 2009 ao conquistar o primeiro título alemão e ganhar espaço na Europa.'],
  'FC Basel' => ['idolos' => ['Alexander Frei', 'Shaqiri', 'Xherdan Shaqiri'], 'trofeus' => [['nome' => 'Super Liga Suíça', 'quantidade' => 20], ['nome' => 'Copa da Suíça', 'quantidade' => 13]], 'historia' => 'Fundado em 1893 em Basel, o clube suíço tornou-se dominante em seu país e conhecido por campanhas europeias consistentes.'],
  'Birmingham City' => ['idolos' => ['Trevor Francis', 'Joe Bradford', 'Kroenke'], 'trofeus' => [['nome' => 'Copa da Liga Inglesa', 'quantidade' => 2]], 'historia' => 'Fundado em 1875, o Birmingham City é um clube tradicional de Inglaterra, conhecido pelo St. Andrew’s e pela forte ligação com a região das Midlands.'],
  'Paysandu' => ['idolos' => ['Quarenta', 'Vandick', 'Róbson'], 'trofeus' => [['nome' => 'Campeonato Brasileiro Série B', 'quantidade' => 2], ['nome' => 'Copa Verde', 'quantidade' => 5]], 'historia' => 'Fundado em 1914 em Belém, o Paysandu é um dos clubes mais tradicionais da Região Norte e tem forte rivalidade histórica com o Remo.'],
  'Remo' => ['idolos' => ['Belterra', 'Mário Silva', 'Evandro'], 'trofeus' => [['nome' => 'Campeonato Brasileiro Série B', 'quantidade' => 1], ['nome' => 'Copa Verde', 'quantidade' => 1]], 'historia' => 'Fundado em 1905 em Belém, o Remo é símbolo do futebol paraense e manteve uma das maiores rivalidades regionais em duelos contra o Paysandu.'],
  'Avaí' => ['idolos' => ['César', 'Nunes', 'Luiz Roberto'], 'trofeus' => [['nome' => 'Campeonato Catarinense', 'quantidade' => 18]], 'historia' => 'Fundado em 1923 em Florianópolis, o Avaí é um dos clubes mais tradicionais de Santa Catarina e tem forte ligação com a Ressacada.'],
  'Goiás' => ['idolos' => ['Túlio Maravilha', 'Romerito', 'Fernandão'], 'trofeus' => [['nome' => 'Campeonato Brasileiro Série B', 'quantidade' => 2], ['nome' => 'Copa Verde', 'quantidade' => 3]], 'historia' => 'Fundado em 1943 em Goiânia, o Goiás levou o futebol do Centro-Oeste a maior visibilidade nacional e teve campanhas fortes nas décadas recentes.'],
  'Cuiabá' => ['idolos' => ['Rodriguinho', 'Deyverson', 'Walter'], 'trofeus' => [['nome' => 'Campeonato Mato-Grossense', 'quantidade' => 4]], 'historia' => 'Fundado em 2001, o Cuiabá representa Mato Grosso na elite nacional e viveu um rápido crescimento esportivo e estrutural.'],
  'América Mineiro' => ['idolos' => ['Tostão', 'Palhinha', 'Gilberto'], 'trofeus' => [['nome' => 'Campeonato Brasileiro Série B', 'quantidade' => 2], ['nome' => 'Campeonato Mineiro', 'quantidade' => 16]], 'historia' => 'Fundado em 1912 em Belo Horizonte, o América Mineiro é um dos clubes tradicionais de Minas Gerais e mantém rivalidade histórica no futebol mineiro.'],
];
?>
<?php
$elencosPorTime = [];
$vinculos = $pdo->query(
  'SELECT j.id, j.numero, j.nome AS jogador, j.posicao,
          atual.nome AS time_atual, jc.time_id AS time_carreira,
          carreira.nome AS time_carreira_nome
   FROM jogadores j
   LEFT JOIN times atual ON atual.id=j.time_id
   LEFT JOIN jogador_carreira jc ON jc.jogador_id=j.id
   LEFT JOIN times carreira ON carreira.id=jc.time_id'
)->fetchAll();
foreach ($vinculos as $vinculo) {
  $idJogador = (int) $vinculo['id'];
  $dadosJogador = [
    'numero' => $vinculo['numero'],
    'nome' => $vinculo['jogador'],
    'posicao' => $vinculo['posicao'],
  ];
  if ($vinculo['time_atual']) {
    $elencosPorTime[$vinculo['time_atual']][$idJogador] = $dadosJogador;
  }
  if ($vinculo['time_carreira_nome']) {
    $elencosPorTime[$vinculo['time_carreira_nome']][$idJogador] = $dadosJogador;
  }
}
?>
<section class="secao">
  <div class="titulo-secao"><div><span class="tag">Clubes</span><h1>Times</h1></div></div>
  <form class="busca-form" method="get">
    <label for="busca-times">Buscar por nome ou cidade</label>
    <input id="busca-times" name="busca_time" list="sugestoes-times-cidades" value="<?= e($_GET['busca_time'] ?? '') ?>" placeholder="Digite ou escolha um time/cidade">
    <datalist id="sugestoes-times-cidades">
      <?php foreach ($opcoesBuscaTimes as $opcao): ?>
        <option value="<?= e($opcao) ?>"></option>
      <?php endforeach; ?>
    </datalist>
    <button class="botao" type="submit">Buscar</button>
    <?php if (!empty($_GET['busca_time'])): ?><a href="/times.php">Limpar</a><?php endif; ?>
  </form>
  <div class="grade-clubes">
    <?php foreach ($times as $time): ?>
      <?php
      $chaveHistorico = $time['nome'];
      if ($chaveHistorico === 'Atlético Mineiro') $chaveHistorico = 'Atlético Mineiro';
      if ($chaveHistorico === 'Vasco da Grama') $chaveHistorico = 'Vasco da Gama';
      $historia = $idolosETrofeus[$chaveHistorico] ?? [
        'idolos' => ['Ídolos não cadastrados'],
        'trofeus' => [['nome' => 'Títulos disponíveis no histórico do clube', 'quantidade' => null]],
        'historia' => 'História detalhada em atualização no cadastro do clube.',
      ];
      $nomeTabela = $time['nome'] === 'Atlético Mineiro' ? 'Atlético-MG' : $time['nome'];
      $estatisticasTime = null;
      foreach ($tabelaBrasileirao2026 as $linhaTabela) {
        if ($linhaTabela['time'] === $nomeTabela) {
          $estatisticasTime = $linhaTabela;
          break;
        }
      }
      $elencoTime = $elencosPorTime[$time['nome']] ?? [];
      ?>
      <article class="card-clube card-time-detalhes">
        <div class="escudo"><?= strtoupper(substr($time['nome'], 0, 1)) ?></div>
        <h3><?= e($time['nome']) ?></h3>
        <p><?= e($time['cidade']) ?> · <?= e($time['estado']) ?></p>
        <small>Fundado em <?= e((string) $time['fundacao']) ?></small>
        <button type="button" class="botao-historia-time" aria-expanded="false">
          Ver detalhes do clube
        </button>
        <div class="painel-historia-time" hidden>
          <p class="texto-historia-time"><?= e($historia['historia']) ?></p>
          <div>
            <span class="tag">Ídolos</span>
            <ul>
              <?php foreach ($historia['idolos'] as $idolo): ?>
                <li><?= e($idolo) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div>
            <span class="tag">Troféus</span>
            <ul>
              <?php foreach ($historia['trofeus'] as $trofeu): ?>
                <li>
                  🏆 <?= e($trofeu['nome']) ?>
                  <?php if ($trofeu['quantidade'] !== null): ?>
                    <strong class="quantidade-titulo"><?= (int) $trofeu['quantidade'] ?>×</strong>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="estatisticas-time">
            <span class="tag">Estatísticas da Série A 2026</span>
            <?php if ($estatisticasTime): ?>
              <div class="grade-estatisticas-time">
                <span><strong><?= (int) $estatisticasTime['posicao'] ?>º</strong>Posição</span>
                <span><strong><?= (int) $estatisticasTime['jogos'] ?></strong>Jogos</span>
                <span><strong><?= (int) $estatisticasTime['vitorias'] ?></strong>Vitórias</span>
                <span><strong><?= (int) $estatisticasTime['empates'] ?></strong>Empates</span>
                <span><strong><?= (int) $estatisticasTime['derrotas'] ?></strong>Derrotas</span>
                <span><strong><?= (int) $estatisticasTime['gols_pro'] ?></strong>Gols pró</span>
                <span><strong><?= (int) $estatisticasTime['gols_contra'] ?></strong>Gols contra</span>
                <span><strong><?= $estatisticasTime['saldo'] > 0 ? '+' : '' ?><?= (int) $estatisticasTime['saldo'] ?></strong>Saldo</span>
                <span><strong><?= (int) $estatisticasTime['pontos'] ?></strong>Pontos</span>
              </div>
            <?php else: ?>
              <p>Estatísticas da Série A indisponíveis para este clube.</p>
            <?php endif; ?>
          </div>
          <div class="escala-time">
            <span class="tag">Jogadores cadastrados</span>
            <?php if ($elencoTime): ?>
              <ul>
                <?php foreach ($elencoTime as $jogadorTime): ?>
                  <li>
                    <?php if ($jogadorTime['numero'] !== null): ?>
                      <strong><?= (int) $jogadorTime['numero'] ?></strong>
                    <?php endif; ?>
                    <?= e($jogadorTime['nome']) ?>
                    <?php if ($jogadorTime['posicao']): ?>
                      <small><?= e($jogadorTime['posicao']) ?></small>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Elenco oficial não cadastrado no sistema.</p>
            <?php endif; ?>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (!$times): ?><p class="vazio">Nenhum time encontrado.</p><?php endif; ?>
  </div>
</section>
<dialog id="dialogo-time" class="dialogo-time">
  <div class="cabecalho-dialogo-time">
    <h2 id="titulo-dialogo-time">Detalhes do clube</h2>
    <button type="button" id="fechar-dialogo-time" class="fechar-dialogo-time" aria-label="Fechar">Fechar</button>
  </div>
  <div id="conteudo-dialogo-time"></div>
</dialog>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const dialogoTime = document.getElementById('dialogo-time');
  const tituloDialogoTime = document.getElementById('titulo-dialogo-time');
  const conteudoDialogoTime = document.getElementById('conteudo-dialogo-time');

  document.querySelectorAll('.botao-historia-time').forEach(botao => {
    botao.addEventListener('click', () => {
      const card = botao.closest('.card-clube');
      const painel = botao.nextElementSibling;
      tituloDialogoTime.textContent = card.querySelector('h3').textContent;
      conteudoDialogoTime.replaceChildren(painel.cloneNode(true));
      conteudoDialogoTime.querySelector('[hidden]')?.removeAttribute('hidden');
      if (typeof dialogoTime.showModal === 'function') dialogoTime.showModal();
    });
  });

  document.getElementById('fechar-dialogo-time').addEventListener('click', () => dialogoTime.close());
  dialogoTime.addEventListener('click', evento => {
    if (evento.target === dialogoTime) dialogoTime.close();
  });
});
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
