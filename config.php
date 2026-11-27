<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function e(mixed $valor): string {
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('FUTNEX_DB_HOST') ?: 'localhost';
    $database = getenv('FUTNEX_DB_NAME') ?: 'futnex_site';
    $user = getenv('FUTNEX_DB_USER') ?: 'root';
    $password = getenv('FUTNEX_DB_PASSWORD') ?: 'root';

    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    return $pdo;
}

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
        http_response_code(400);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }
}

$pdo = db();
$busca = trim((string) ($_GET['busca'] ?? ''));
$categoriaFiltro = trim((string) ($_GET['categoria'] ?? ''));
$filtrosNoticias = [];
$parametrosNoticias = [];
if ($busca !== '') {
    $filtrosNoticias[] = '(titulo LIKE :titulo_busca OR resumo LIKE :resumo_busca)';
    $parametrosNoticias['titulo_busca'] = '%' . $busca . '%';
    $parametrosNoticias['resumo_busca'] = '%' . $busca . '%';
}
if ($categoriaFiltro !== '') {
    $filtrosNoticias[] = 'categoria = :categoria';
    $parametrosNoticias['categoria'] = $categoriaFiltro;
}
$sqlNoticias = 'SELECT id, categoria, titulo, resumo, conteudo, imagem, criado_em FROM noticias';
if ($filtrosNoticias) {
    $sqlNoticias .= ' WHERE ' . implode(' AND ', $filtrosNoticias);
}
$sqlNoticias .= ' ORDER BY criado_em DESC, id DESC';
$stmtNoticias = $pdo->prepare($sqlNoticias);
$stmtNoticias->execute($parametrosNoticias);
$noticias = $stmtNoticias->fetchAll();
$categoriasNoticias = $pdo->query(
    'SELECT DISTINCT categoria FROM noticias ORDER BY categoria'
)->fetchAll(PDO::FETCH_COLUMN);

$timeFiltro = filter_var($_GET['time_id'] ?? null, FILTER_VALIDATE_INT);
if ($timeFiltro) {
    $stmt = $pdo->prepare(
        'SELECT j.id, j.numero, j.nome, j.nacionalidade, j.posicao,
                j.valor_mercado, atual.nome AS time
         FROM jogadores j
         LEFT JOIN times atual ON atual.id=j.time_id
         WHERE j.time_id=:current_time_id
            OR EXISTS (
              SELECT 1 FROM jogador_carreira jc
              WHERE jc.jogador_id=j.id AND jc.time_id=:career_time_id
            )
         ORDER BY j.nome'
    );
    $stmt->execute([
        'current_time_id' => $timeFiltro,
        'career_time_id' => $timeFiltro,
    ]);
    $jogadores = $stmt->fetchAll();
} else {
    $jogadores = $pdo->query(
        'SELECT j.id, j.numero, j.nome, j.nacionalidade, j.posicao,
                j.valor_mercado, t.nome AS time
         FROM jogadores j LEFT JOIN times t ON t.id=j.time_id ORDER BY j.nome'
    )->fetchAll();
}
$historicosClubes = [];
$historicos = $pdo->query(
    'SELECT jc.jogador_id, t.nome AS time, jc.ano_inicio, jc.ano_fim
     FROM jogador_carreira jc
     INNER JOIN times t ON t.id=jc.time_id
     ORDER BY jc.jogador_id, jc.ano_inicio, jc.id'
)->fetchAll();
foreach ($historicos as $historico) {
    $historicosClubes[(int) $historico['jogador_id']][] = $historico;
}

$buscaTime = trim((string) ($_GET['busca_time'] ?? ''));
if ($buscaTime !== '') {
    $stmt = $pdo->prepare(
        'SELECT id, nome, cidade, estado, fundacao FROM times
         WHERE nome LIKE :nome_busca OR cidade LIKE :cidade_busca ORDER BY nome'
    );
    $stmt->execute(['nome_busca' => '%' . $buscaTime . '%', 'cidade_busca' => '%' . $buscaTime . '%']);
    $times = $stmt->fetchAll();
} else {
    $times = $pdo->query(
        'SELECT id, nome, cidade, estado, fundacao FROM times ORDER BY nome'
    )->fetchAll();
}
$opcoesBuscaTimes = $pdo->query(
    'SELECT nome AS valor FROM times
     UNION
     SELECT cidade AS valor FROM times WHERE cidade IS NOT NULL AND cidade <> \'\' 
     ORDER BY valor'
)->fetchAll(PDO::FETCH_COLUMN);

$tabelaBrasileirao2026 = [
    ['posicao' => 1, 'time' => 'Flamengo', 'jogos' => 36, 'vitorias' => 24, 'empates' => 8, 'derrotas' => 4, 'gols_pro' => 74, 'gols_contra' => 31, 'saldo' => 43, 'pontos' => 80],
    ['posicao' => 2, 'time' => 'Palmeiras', 'jogos' => 37, 'vitorias' => 21, 'empates' => 11, 'derrotas' => 5, 'gols_pro' => 64, 'gols_contra' => 29, 'saldo' => 35, 'pontos' => 74],
    ['posicao' => 3, 'time' => 'Cruzeiro', 'jogos' => 36, 'vitorias' => 20, 'empates' => 6, 'derrotas' => 10, 'gols_pro' => 59, 'gols_contra' => 50, 'saldo' => 9, 'pontos' => 66],
    ['posicao' => 4, 'time' => 'Fluminense', 'jogos' => 36, 'vitorias' => 18, 'empates' => 9, 'derrotas' => 9, 'gols_pro' => 57, 'gols_contra' => 42, 'saldo' => 15, 'pontos' => 63],
    ['posicao' => 5, 'time' => 'Athletico Paranaense', 'jogos' => 36, 'vitorias' => 16, 'empates' => 9, 'derrotas' => 11, 'gols_pro' => 56, 'gols_contra' => 47, 'saldo' => 9, 'pontos' => 57],
    ['posicao' => 6, 'time' => 'Bahia', 'jogos' => 36, 'vitorias' => 14, 'empates' => 12, 'derrotas' => 10, 'gols_pro' => 50, 'gols_contra' => 46, 'saldo' => 4, 'pontos' => 54],
    ['posicao' => 7, 'time' => 'Atlético-MG', 'jogos' => 36, 'vitorias' => 14, 'empates' => 10, 'derrotas' => 12, 'gols_pro' => 45, 'gols_contra' => 41, 'saldo' => 4, 'pontos' => 52],
    ['posicao' => 8, 'time' => 'Red Bull Bragantino', 'jogos' => 36, 'vitorias' => 14, 'empates' => 8, 'derrotas' => 14, 'gols_pro' => 46, 'gols_contra' => 41, 'saldo' => 5, 'pontos' => 50],
    ['posicao' => 9, 'time' => 'Santos', 'jogos' => 36, 'vitorias' => 13, 'empates' => 11, 'derrotas' => 12, 'gols_pro' => 51, 'gols_contra' => 51, 'saldo' => 0, 'pontos' => 50],
    ['posicao' => 10, 'time' => 'São Paulo', 'jogos' => 37, 'vitorias' => 13, 'empates' => 9, 'derrotas' => 15, 'gols_pro' => 43, 'gols_contra' => 42, 'saldo' => 1, 'pontos' => 48],
    ['posicao' => 11, 'time' => 'Vasco da Gama', 'jogos' => 36, 'vitorias' => 13, 'empates' => 8, 'derrotas' => 15, 'gols_pro' => 47, 'gols_contra' => 52, 'saldo' => -5, 'pontos' => 47],
    ['posicao' => 12, 'time' => 'Coritiba', 'jogos' => 36, 'vitorias' => 12, 'empates' => 9, 'derrotas' => 15, 'gols_pro' => 41, 'gols_contra' => 52, 'saldo' => -11, 'pontos' => 45],
    ['posicao' => 13, 'time' => 'Vitória', 'jogos' => 36, 'vitorias' => 12, 'empates' => 8, 'derrotas' => 16, 'gols_pro' => 42, 'gols_contra' => 54, 'saldo' => -12, 'pontos' => 44],
    ['posicao' => 14, 'time' => 'Botafogo', 'jogos' => 36, 'vitorias' => 11, 'empates' => 10, 'derrotas' => 15, 'gols_pro' => 47, 'gols_contra' => 58, 'saldo' => -11, 'pontos' => 43],
    ['posicao' => 15, 'time' => 'Corinthians', 'jogos' => 36, 'vitorias' => 10, 'empates' => 11, 'derrotas' => 15, 'gols_pro' => 41, 'gols_contra' => 43, 'saldo' => -2, 'pontos' => 41],
    ['posicao' => 16, 'time' => 'Grêmio', 'jogos' => 36, 'vitorias' => 10, 'empates' => 11, 'derrotas' => 15, 'gols_pro' => 40, 'gols_contra' => 47, 'saldo' => -7, 'pontos' => 41],
    ['posicao' => 17, 'time' => 'Mirassol', 'jogos' => 36, 'vitorias' => 10, 'empates' => 11, 'derrotas' => 15, 'gols_pro' => 39, 'gols_contra' => 49, 'saldo' => -10, 'pontos' => 41],
    ['posicao' => 18, 'time' => 'Internacional', 'jogos' => 36, 'vitorias' => 9, 'empates' => 11, 'derrotas' => 16, 'gols_pro' => 41, 'gols_contra' => 49, 'saldo' => -8, 'pontos' => 38],
    ['posicao' => 19, 'time' => 'Remo', 'jogos' => 37, 'vitorias' => 7, 'empates' => 11, 'derrotas' => 19, 'gols_pro' => 42, 'gols_contra' => 64, 'saldo' => -22, 'pontos' => 32],
    ['posicao' => 20, 'time' => 'Chapecoense', 'jogos' => 37, 'vitorias' => 5, 'empates' => 9, 'derrotas' => 23, 'gols_pro' => 37, 'gols_contra' => 74, 'saldo' => -37, 'pontos' => 24],
];

$resultadosAnteriores = [
    [
        'data' => '26/11/2026', 'casa' => 'São Paulo', 'placar_casa' => 3, 'fora' => 'Remo', 'placar_fora' => 1,
        'estadio' => 'Estádio Cícero Pompeu de Toledo', 'status' => 'Encerrado',
        'estatisticas' => [
            'Posse de bola' => ['62.5%', '37.5%'],
            'Finalizações' => ['9', '6'],
            'Chutes no alvo' => ['4', '1'],
            'Escanteios' => ['4', '3'],
            'Faltas' => ['18', '14'],
            'Passes certos' => ['553', '294'],
            'Cartões amarelos' => ['1', '3'],
            'Defesas' => ['0', '1'],
            'Impedimentos' => ['5', '1'],
        ],
        'resumo' => "São Paulo venceu o Remo por 3 a 1. Jonathan Calleri marcou aos 39 e 90+5 minutos, Ryan Francisco aos 87, e Gabriel Poveda fez o gol do Remo aos 55.",
    ],
    [
        'data' => '25/11/2026', 'casa' => 'Atlético-MG', 'placar_casa' => 1, 'fora' => 'Corinthians', 'placar_fora' => 1,
        'estadio' => 'Arena MRV', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['52.3%', '47.7%'], 'Finalizações' => ['23', '15'], 'Chutes no alvo' => ['4', '3'], 'Escanteios' => ['14', '0'], 'Faltas' => ['16', '9'], 'Assistências' => ['1', '1']],
        'escala_casa' => ['formacao' => '4-1-4-1', 'titulares' => ['Éverson','Ruan','Léo Duarte','Tomas Perez','Renan Lodi','Natanael','Kevin Castaño','Mamady Cissé','Tomás Cuello','Bernard','Thiago Borbas'], 'substitutos' => ['Alexsander','Victor Hugo','Igor Gomes'], 'completo' => ['Éverson','Ruan','Léo Duarte','Tomas Perez','Renan Lodi','Natanael','Kevin Castaño','Mamady Cissé','Tomás Cuello','Bernard','Thiago Borbas','Alexsander','Lyanco','Ángelo Preciado','Dudu','Gustavo Scarpa','Kauã Pascini','Gabriel Delfim','Vitor Hugo','Victor Hugo','Igor Gomes','Mateo Casierra']],
        'escala_fora' => ['formacao' => '4-2-3-1', 'titulares' => ['Hugo Souza','Gustavo Henrique','João Pedro','Fabrizio Angileri','Mateuzinho','Vitinho','Raniele','Breno Bidon','Memphis Depay','Matheus Bidu','Kaio'], 'substitutos' => ['André','Pedro Milans','Rodrigo Garro','Jesse Lingard'], 'completo' => ['Hugo Souza','Gustavo Henrique','João Pedro','Fabrizio Angileri','Mateuzinho','Vitinho','Raniele','Breno Bidon','Memphis Depay','Matheus Bidu','Kaio','Pedro Raúl','André','Gui Negão','Dieguinho','Matheus Pereira','André Ramalho','Pedro Milans','Kauê Camargo','Rodrigo Garro','Jesse Lingard','Alex Santana','Charles']],
        'resumo' => 'Atlético-MG empatou com o Corinthians por 1 a 1. Tomás Cuello marcou para os mineiros aos 90+2; Kaio marcou para o Corinthians aos 70 minutos.',
    ],
    [
        'data' => '24/11/2026', 'casa' => 'Chapecoense', 'placar_casa' => 3, 'fora' => 'Palmeiras', 'placar_fora' => 0,
        'estadio' => 'Arena Condá', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['43.5%', '56.5%'], 'Finalizações' => ['10', '26'], 'Chutes no alvo' => ['8', '6'], 'Escanteios' => ['5', '10'], 'Faltas' => ['18', '12'], 'Assistências' => ['2', '0']],
        'escala_casa' => ['formacao' => '3-4-2-1', 'titulares' => ['Matheus','Eduardo Doma','Kauan','Victor Caetano','David','Camilo','Bruno Pacheco','Heitor','Túlio Eduardo Silva De Oliveira','Giovanni Augusto','Max'], 'substitutos' => ['Bruno','Fernando','Rubens Tadeu Hartmann Ricoldi','Miguel Carvalho','Marcinho'], 'completo' => ['Matheus','Eduardo Doma','Kauan','Victor Caetano','David','Camilo','Bruno Pacheco','Heitor','Túlio Eduardo Silva De Oliveira','Giovanni Augusto','Max','Bruno','Fernando','Juan Ferreira Leite','Rubens Tadeu Hartmann Ricoldi','Mancha','Vinicius Eduardo De Almeida Gomes Da Silva','Rosivan Macedo Soares','Rodrigo Endrio Azevedo Tiago','Everton','Miguel Carvalho','Gabriel Werner','Marcinho']],
        'escala_fora' => ['formacao' => '3-4-2-1', 'titulares' => ['Marcelo Lomba','Luiz Benedetti','Kone Zie Mohamed','Emiliano Martínez','Luis Felipe','Larson','Khellven','Felipe Anderson','Heittor','Fabiano','Erick Machado'], 'substitutos' => ['Miguel Bilong','André Lucas','Rafael Coutinho','Victor Gabriel','Geovanne De Souza Soares'], 'completo' => ['Marcelo Lomba','Luiz Benedetti','Kone Zie Mohamed','Emiliano Martínez','Luis Felipe','Larson','Khellven','Felipe Anderson','Heittor','Fabiano','Erick Machado','Miguel Bilong','André Lucas','Rafael Coutinho','Kauan Lima','Robson','Bruno Bertinato','Victor Gabriel','Geovanne De Souza Soares']],
        'resumo' => 'A Chapecoense venceu o Palmeiras por 3 a 0 em casa.',
    ],
    [
        'data' => '23/11/2026', 'casa' => 'Internacional', 'placar_casa' => 2, 'fora' => 'Vitória', 'placar_fora' => 5,
        'estadio' => 'Estádio Beira-Rio', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['69.6%', '30.4%'], 'Finalizações' => ['23', '12'], 'Chutes no alvo' => ['4', '8'], 'Escanteios' => ['11', '5'], 'Faltas' => ['19', '14'], 'Assistências' => ['1', '4']],
        'escala_casa' => ['formacao' => '3-4-2-1', 'titulares' => ['Kauan','Gabriel Mercado','Juninho','Bruno Gomes','Rodrigo Villagra','Ronaldo','Matheus Bahia','Brian Aguirre','Alerrando','Johan Carbonero','Vitinho'], 'substitutos' => ['Niclas Eliasson','João Bezerra','Paulinho','Calebe','Thiago Maia'], 'completo' => ['Kauan','Gabriel Mercado','Juninho','Bruno Gomes','Rodrigo Villagra','Ronaldo','Matheus Bahia','Brian Aguirre','Alerrando','Johan Carbonero','Vitinho','Bruno Henrique','Niclas Eliasson','Guillermo Maripán','João Bezerra','João Victor','Anthoni','Paulinho','Antonio Sanabria','Allex','Calebe','Victor Gabriel','Thiago Maia']],
        'escala_fora' => ['formacao' => '4-2-3-1', 'titulares' => ['Lucas Arcanjo','Emanuel Britez','Cacá','Ramon','Jamerson','Matheus','Emmanuel Martínez','Gabriel Baralhas','Renê','Diego Tarzia','Erick'], 'substitutos' => ['Zé Vitor','Caique','Renato Kayzer','Luan Cândido','Marinho'], 'completo' => ['Lucas Arcanjo','Emanuel Britez','Cacá','Ramon','Jamerson','Matheus','Emmanuel Martínez','Gabriel Baralhas','Renê','Diego Tarzia','Erick','Zé Vitor','Ignacio Laquintana','Caique','Renato Kayzer','Osvaldo','Fabrício Santos','Luan Cândido','Marinho','Gean','Camutanga','Alejandro Almaraz','Yuri Sena']],
        'resumo' => 'O Vitória venceu o Internacional por 5 a 2 no Beira-Rio.',
    ],
    [
        'data' => '23/11/2026', 'casa' => 'Botafogo', 'placar_casa' => 1, 'fora' => 'São Paulo', 'placar_fora' => 1,
        'estadio' => 'Estádio Nilton Santos', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['56.9%', '43.1%'], 'Finalizações' => ['23', '13'], 'Chutes no alvo' => ['7', '7'], 'Escanteios' => ['6', '3'], 'Faltas' => ['22', '19'], 'Assistências' => ['0', '1']],
        'escala_casa' => ['formacao' => '3-4-3', 'titulares' => ['Gabriel Batista','Gabriel Justino','Fernando Marçal','Nahuel Ferraresi','Cristian Medina','Allan','Alex Telles','Vitinho','Tiquinho Soares','Matheus Martins','Hakim Ziyech'], 'substitutos' => ['Kauan Toledo','Danilo Santos','Arthur Cabral','Álvaro Montoro','Edenilson'], 'completo' => ['Gabriel Batista','Gabriel Justino','Fernando Marçal','Nahuel Ferraresi','Cristian Medina','Allan','Alex Telles','Vitinho','Tiquinho Soares','Matheus Martins','Hakim Ziyech','Kauan Toledo','Domingos','Mateo Ponte','Arthur Chaves','Danilo Santos','Paulo Victor','Warleson','Arthur Cabral','Álvaro Montoro','Kaio','Júnior Santos','Edenilson']],
        'escala_fora' => ['formacao' => '3-5-2', 'titulares' => ['Rafael','Robert Arboleda','Luis Osorio','Newton','Damián Bobadilla','Djhordney Ferreira','Pablo Maia','Iago','Pedro Lima','Jonathan Calleri','Luciano'], 'substitutos' => ['Bezerra','Lucca Marques','Danielzinho','Wendell'], 'completo' => ['Rafael','Robert Arboleda','Luis Osorio','Newton','Damián Bobadilla','Djhordney Ferreira','Pablo Maia','Iago','Pedro Lima','Jonathan Calleri','Luciano','Pedro Ferreira','Lucas Ramon','Bezerra','Lucca Marques','Ferreira','Carlos Coronel','Danielzinho','Gustavo Santana','Joao Victor','Wendell','Ryan Francisco','Isac']],
        'resumo' => 'Botafogo e São Paulo empataram por 1 a 1.',
    ],
    [
        'data' => '24/11/2026', 'casa' => 'Santos', 'placar_casa' => 0, 'fora' => 'Grêmio', 'placar_fora' => 2,
        'estadio' => 'Estádio Cícero Pompeu de Toledo', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['70.9%', '29.1%'], 'Finalizações' => ['22', '8'], 'Chutes no alvo' => ['7', '5'], 'Escanteios' => ['9', '2'], 'Faltas' => ['15', '14'], 'Assistências' => ['0', '1']],
        'escala_casa' => ['formacao' => '4-2-3-1', 'titulares' => ['Diogenes','Willian Arão','João Alencar','Luan Peres','Rodinei','Neymar','Arthur','Philippe Coutinho','Gabriel Barbosa','Everton','Gabriel Bontempo'], 'substitutos' => ['Gabriel Menino','Benjamín Rollheiser','Igor','Gonzalo Escobar','Rony'], 'completo' => ['Diogenes','Willian Arão','João Alencar','Luan Peres','Rodinei','Neymar','Arthur','Philippe Coutinho','Gabriel Barbosa','Everton','Gabriel Bontempo','Gabriel Menino','Christian Oliva','Lima','João Schmidt','Benjamín Rollheiser','Igor','Thaciano','João Paulo','João Ananias','Gonzalo Escobar','Rony','Gustavo Caballero']],
        'escala_fora' => ['formacao' => '4-1-4-1', 'titulares' => ['Weverton','Wálter Kannemann','Wallace','Erick Noriega','Marlon','Diego Caito','Dodi','Mathías Villasanti','Francis Amuzu','Cristian Pavón','Carlos Vinícius'], 'substitutos' => ['Mateus Tetê','Fabián Balbuena','Caio Paulista','Martin Braithwaite','Danilo Barbosa'], 'completo' => ['Weverton','Wálter Kannemann','Wallace','Erick Noriega','Marlon','Diego Caito','Dodi','Mathías Villasanti','Francis Amuzu','Cristian Pavón','Carlos Vinícius','Gustavo Martins','Mateus Tetê','Wagner Leonardo','Gabriel Grando','Fabián Balbuena','Pedro Gabriel','Filip Krovinovic','Caio Paulista','Martin Braithwaite','Jovane Cabral','Danilo Barbosa','Marcos Rocha']],
        'resumo' => 'O Grêmio venceu o Santos por 2 a 0 fora de casa.',
    ],
    [
        'data' => '22/11/2026', 'casa' => 'Athletico-PR', 'placar_casa' => 4, 'fora' => 'Remo', 'placar_fora' => 2,
        'estadio' => 'Arena da Baixada', 'status' => 'Encerrado',
        'estatisticas' => ['Posse de bola' => ['51.2%', '48.8%'], 'Finalizações' => ['14', '14'], 'Chutes no alvo' => ['8', '7'], 'Escanteios' => ['4', '4'], 'Faltas' => ['10', '17'], 'Assistências' => ['2', '1']],
        'escala_casa' => ['formacao' => '3-4-2-1', 'titulares' => ['Mycael','Dantas','Lucas Esquivel','Arthur Dias','Luiz Gustavo','Jádson','Stiven Mendoza','Gastón Benavídez','Kerwin Vargas','Bruno Zapelli','Leozinho'], 'substitutos' => ['Leonardo Derik','João Cruz','Jorge Rivaldo','Gilberto','Filipinho'], 'completo' => ['Mycael','Dantas','Lucas Esquivel','Arthur Dias','Luiz Gustavo','Jádson','Stiven Mendoza','Gastón Benavídez','Kerwin Vargas','Bruno Zapelli','Leozinho','Léo','Diogo Riquelme','Leonardo Derik','Santos','Chiqueti','João Cruz','Renan','Jorge Rivaldo','Juan Felipe Aguirre','Gilberto','Eduardo','Filipinho']],
        'escala_fora' => ['formacao' => '5-4-1', 'titulares' => ['Marcelo Rangel','José Welison','Duplexe Tchamba','Zé Ivaldo','Mayk','Marcelinho','Patrick','Edson Fernando','Jajá','David Francisco','Gabriel Taliari'], 'substitutos' => ['Zé Ricardo','Gabriel Poveda','Yago Pikachu','Antonio Galeano','Marllon'], 'completo' => ['Marcelo Rangel','José Welison','Duplexe Tchamba','Zé Ivaldo','Mayk','Marcelinho','Patrick','Edson Fernando','Jajá','David Francisco','Gabriel Taliari','Ygor Vinhas','Eduardo','Ivan','João','Jaderson','Marlon Lopes','Zé Ricardo','Gabriel Poveda','Yago Pikachu','Vitor Bueno','Antonio Galeano','Marllon']],
        'resumo' => 'Athletico-PR venceu o Remo por 4 a 2 na Arena da Baixada.',
    ],
];

$jogos = [
    [
        'hora' => 'AMANHÃ · 20:00', 'casa' => 'Fluminense', 'placar' => 'VS', 'fora' => 'Cruzeiro',
        'campeonato' => 'Brasileirão - Série A', 'status' => 'amanha', 'estadio' => 'Maracanã',
        'transmissao' => 'Confira a programação oficial', 'clima' => 'A confirmar', 'minuto' => '',
        'estatisticas' => ['Posse de bola' => ['—', '—'], 'Finalizações' => ['—', '—'], 'No alvo' => ['—', '—'], 'Escanteios' => ['—', '—'], 'Faltas' => ['—', '—'], 'Passes certos' => ['—', '—'], 'Cartões amarelos' => ['—', '—'], 'Defesas' => ['—', '—']],
        'escala_casa' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'escala_fora' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'resumo' => 'Confronto oficial da Série A do Campeonato Brasileiro de 2026.',
    ],
    [
        'hora' => 'DOM · 21:00', 'casa' => 'Botafogo', 'placar' => 'VS', 'fora' => 'Bahia',
        'campeonato' => 'Brasileirão - Série A', 'status' => 'futuro', 'estadio' => 'Estádio Nilton Santos',
        'transmissao' => 'Confira a programação oficial', 'clima' => 'A confirmar', 'minuto' => '',
        'estatisticas' => ['Posse de bola' => ['—', '—'], 'Finalizações' => ['—', '—'], 'No alvo' => ['—', '—'], 'Escanteios' => ['—', '—'], 'Faltas' => ['—', '—'], 'Passes certos' => ['—', '—'], 'Cartões amarelos' => ['—', '—'], 'Defesas' => ['—', '—']],
        'escala_casa' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'escala_fora' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'resumo' => 'Confronto oficial da Série A do Campeonato Brasileiro de 2026.',
    ],
    [
        'hora' => 'DOM · 16:00', 'casa' => 'Coritiba', 'placar' => 'VS', 'fora' => 'Flamengo',
        'campeonato' => 'Brasileirão - Série A', 'status' => 'futuro', 'estadio' => 'Couto Pereira',
        'transmissao' => 'Confira a programação oficial', 'clima' => 'A confirmar', 'minuto' => '',
        'estatisticas' => ['Posse de bola' => ['—', '—'], 'Finalizações' => ['—', '—'], 'No alvo' => ['—', '—'], 'Escanteios' => ['—', '—'], 'Faltas' => ['—', '—'], 'Passes certos' => ['—', '—'], 'Cartões amarelos' => ['—', '—'], 'Defesas' => ['—', '—']],
        'escala_casa' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'escala_fora' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'resumo' => 'Confronto oficial da Série A do Campeonato Brasileiro de 2026.',
    ],
    [
        'hora' => 'DOM · 16:00', 'casa' => 'Vitória', 'placar' => 'VS', 'fora' => 'Santos',
        'campeonato' => 'Brasileirão - Série A', 'status' => 'futuro', 'estadio' => 'Barradão',
        'transmissao' => 'Confira a programação oficial', 'clima' => 'A confirmar', 'minuto' => '',
        'estatisticas' => ['Posse de bola' => ['—', '—'], 'Finalizações' => ['—', '—'], 'No alvo' => ['—', '—'], 'Escanteios' => ['—', '—'], 'Faltas' => ['—', '—'], 'Passes certos' => ['—', '—'], 'Cartões amarelos' => ['—', '—'], 'Defesas' => ['—', '—']],
        'escala_casa' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'escala_fora' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'resumo' => 'Confronto oficial da Série A do Campeonato Brasileiro de 2026.',
    ],
    [
        'hora' => 'DOM · 18:30', 'casa' => 'Corinthians', 'placar' => 'VS', 'fora' => 'Grêmio',
        'campeonato' => 'Brasileirão - Série A', 'status' => 'futuro', 'estadio' => 'Neo Química Arena',
        'transmissao' => 'Confira a programação oficial', 'clima' => 'A confirmar', 'minuto' => '',
        'estatisticas' => ['Posse de bola' => ['—', '—'], 'Finalizações' => ['—', '—'], 'No alvo' => ['—', '—'], 'Escanteios' => ['—', '—'], 'Faltas' => ['—', '—'], 'Passes certos' => ['—', '—'], 'Cartões amarelos' => ['—', '—'], 'Defesas' => ['—', '—']],
        'escala_casa' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'escala_fora' => ['Escalação oficial não publicada', 'Reserva: confira os elencos oficiais'],
        'resumo' => 'Confronto oficial da Série A do Campeonato Brasileiro de 2026.',
    ],
];
