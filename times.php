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
];
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
      $historia = $idolosETrofeus[$chaveHistorico] ?? [
        'idolos' => ['Ídolos não cadastrados'],
        'trofeus' => [['nome' => 'Títulos disponíveis no histórico do clube', 'quantidade' => null]],
        'historia' => 'História detalhada em atualização no cadastro do clube.',
      ];
      ?>
      <article class="card-clube card-time-detalhes">
        <div class="escudo"><?= strtoupper(substr($time['nome'], 0, 1)) ?></div>
        <h3><?= e($time['nome']) ?></h3>
        <p><?= e($time['cidade']) ?> · <?= e($time['estado']) ?></p>
        <small>Fundado em <?= e((string) $time['fundacao']) ?></small>
        <button type="button" class="botao-historia-time" aria-expanded="false">
          Ver ídolos e troféus
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
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (!$times): ?><p class="vazio">Nenhum time encontrado.</p><?php endif; ?>
  </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.botao-historia-time').forEach(botao => {
    botao.addEventListener('click', () => {
      const painel = botao.nextElementSibling;
      const aberto = botao.getAttribute('aria-expanded') === 'true';
      document.querySelectorAll('.botao-historia-time').forEach(outro => {
        if (outro !== botao) {
          outro.setAttribute('aria-expanded', 'false');
          outro.textContent = 'Ver ídolos e troféus';
          outro.nextElementSibling.hidden = true;
        }
      });
      botao.setAttribute('aria-expanded', String(!aberto));
      botao.textContent = aberto ? 'Ver ídolos e troféus' : 'Ocultar ídolos e troféus';
      painel.hidden = aberto;
    });
  });
});
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
