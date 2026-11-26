<?php
require __DIR__ . '/config.php';
$pagina = 'Inicio';
require __DIR__ . '/partials/header.php';
?>
<section class="hero">
  <div>
    <span class="tag">Central do futebol</span>
    <h1>O futebol começa aqui.</h1>
    <p>Partidas, clubes, jogadores e as principais notícias em um só lugar.</p>
    <a class="botao" href="#noticias">Ver últimas notícias</a>
  </div>
</section>

<section id="noticias" class="secao">
  <div class="titulo-secao">
    <div>
      <span class="tag">Cobertura</span>
      <h2>Últimas notícias</h2>
    </div>
  </div>
  <div class="grade-noticias">
    <?php foreach (array_slice($noticias, 0, 3) as $noticia): ?>
      <article class="card noticia-principal">
        <img src="<?= e($noticia['imagem']) ?>" alt="<?= e($noticia['titulo']) ?>">
        <div class="card-corpo">
          <span class="categoria"><?= e($noticia['categoria']) ?></span>
          <h3><?= e($noticia['titulo']) ?></h3>
          <p><?= e($noticia['resumo']) ?></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="secao painel-duplo">
  <div>
    <span class="tag">Rodada</span>
    <h2>Jogos em destaque</h2>
    <p class="nota-jogos-reais">Calendário consultado no ESPN para a Série A de 2026. Horários podem mudar.</p>
    <div class="filtros-jogos" role="group" aria-label="Filtrar partidas">
      <button type="button" class="filtro-jogo ativo" data-filtro="todos">Todos</button>
      <button type="button" class="filtro-jogo" data-filtro="hoje">Hoje</button>
      <button type="button" class="filtro-jogo" data-filtro="amanha">Amanhã</button>
      <button type="button" class="filtro-jogo" data-filtro="futuro">Próximos</button>
      <button type="button" class="filtro-jogo" data-filtro="favoritos">Favoritos</button>
    </div>
    <div class="lista-jogos cards-jogos">
      <?php foreach ($jogos as $jogo): ?>
        <?php $idJogo = md5($jogo['casa'] . $jogo['fora']); ?>
        <article class="jogo card-jogo"
          data-status="<?= e($jogo['status']) ?>"
          data-id="<?= $idJogo ?>"
          data-casa="<?= e($jogo['casa']) ?>"
          data-fora="<?= e($jogo['fora']) ?>"
          data-hora="<?= e($jogo['hora']) ?>"
          data-campeonato="<?= e($jogo['campeonato']) ?>"
          data-estadio="<?= e($jogo['estadio']) ?>"
          data-transmissao="<?= e($jogo['transmissao']) ?>"
          data-clima="<?= e($jogo['clima']) ?>"
          data-resumo="<?= e($jogo['resumo']) ?>"
          data-estatisticas='<?= e(json_encode($jogo['estatisticas'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE)) ?>'
          data-escala-casa='<?= e(json_encode($jogo['escala_casa'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE)) ?>'
          data-escala-fora='<?= e(json_encode($jogo['escala_fora'], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE)) ?>'>
          <div class="jogo-cabecalho">
            <div>
              <span class="status-partida">PROGRAMADO</span>
              <span class="hora"><?= e($jogo['hora']) ?></span>
            </div>
            <div class="acoes-jogo">
              <button type="button" class="favorito-jogo" title="Adicionar aos favoritos" aria-label="Adicionar <?= e($jogo['casa']) ?> e <?= e($jogo['fora']) ?> aos favoritos">☆</button>
            </div>
          </div>
          <div class="confronto">
            <strong><?= e($jogo['casa']) ?></strong>
            <strong><?= e($jogo['fora']) ?></strong>
          </div>
          <div class="meta-jogo">
            <span><?= e($jogo['campeonato']) ?></span>
            <span><?= e($jogo['estadio']) ?></span>
            <span><?= e($jogo['clima']) ?></span>
          </div>
          <button type="button" class="detalhes-jogo" aria-expanded="false">Ver detalhes</button>
          <div class="painel-jogo" hidden>
            <div class="abas-jogo">
              <button type="button" class="aba-jogo ativa" data-aba="resumo">Resumo</button>
              <button type="button" class="aba-jogo" data-aba="estatisticas">Estatísticas</button>
              <button type="button" class="aba-jogo" data-aba="escalacoes">Escalações</button>
            </div>
            <div class="conteudo-aba ativo" data-conteudo="resumo">
              <p><?= e($jogo['resumo']) ?></p>
              <p><strong>Transmissão:</strong> <?= e($jogo['transmissao']) ?></p>
            </div>
            <div class="conteudo-aba" data-conteudo="estatisticas">
              <?php foreach ($jogo['estatisticas'] as $label => [$valorCasa, $valorFora]): ?>
                <div class="estatistica">
                  <strong><?= e($valorCasa) ?></strong>
                  <span><?= e($label) ?></span>
                  <strong><?= e($valorFora) ?></strong>
                </div>
              <?php endforeach; ?>
              <p class="nota-estatisticas">Dados estatísticos indicativos.</p>
            </div>
            <div class="conteudo-aba" data-conteudo="escalacoes">
              <div class="escalacoes">
                <ul><li class="titulo-escalacao"><?= e($jogo['casa']) ?></li><?php foreach ($jogo['escala_casa'] as $jogador): ?><li><?= e($jogador) ?></li><?php endforeach; ?></ul>
                <ul><li class="titulo-escalacao"><?= e($jogo['fora']) ?></li><?php foreach ($jogo['escala_fora'] as $jogador): ?><li><?= e($jogador) ?></li><?php endforeach; ?></ul>
              </div>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <section class="resultados-anteriores" aria-labelledby="titulo-resultados">
      <div class="titulo-favoritos">
        <div>
          <span class="tag">Encerrados</span>
          <h3 id="titulo-resultados">Jogos anteriores</h3>
        </div>
        <button type="button" id="alternar-resultados" class="alternar-resultados" aria-expanded="true" aria-controls="lista-resultados">
          Ocultar jogos
        </button>
      </div>
      <div id="lista-resultados" class="lista-resultados">
        <?php foreach ($resultadosAnteriores as $resultado): ?>
          <article class="resultado-partida">
            <div class="resultado-cabecalho">
              <div>
                <span class="status-encerrado"><?= e($resultado['status']) ?></span>
                <span class="hora"><?= e($resultado['data']) ?></span>
              </div>
              <span class="estadio-resultado"><?= e($resultado['estadio']) ?></span>
            </div>
            <div class="resultado-placar">
              <strong><?= e($resultado['casa']) ?></strong>
              <div><b><?= e($resultado['placar_casa']) ?></b><b><?= e($resultado['placar_fora']) ?></b></div>
              <strong><?= e($resultado['fora']) ?></strong>
            </div>
            <button type="button" class="detalhes-resultado" aria-expanded="false">Ver estatísticas</button>
            <div class="painel-resultado" hidden>
              <p><?= e($resultado['resumo']) ?></p>
              <?php foreach ($resultado['estatisticas'] as $label => [$valorCasa, $valorFora]): ?>
                <div class="estatistica resultado-estatistica">
                  <strong><?= e($valorCasa) ?></strong>
                  <span><?= e($label) ?></span>
                  <strong><?= e($valorFora) ?></strong>
                </div>
              <?php endforeach; ?>
              <p class="nota-estatisticas">Fonte: ESPN · Dados oficiais da partida.</p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <section id="favoritos-partidas" class="favoritos-partidas" aria-labelledby="titulo-favoritos">
      <div class="titulo-favoritos">
        <div>
          <span class="tag">Sua seleção</span>
          <h3 id="titulo-favoritos">Partida favorita</h3>
        </div>
        <span id="quantidade-favoritos" class="quantidade-favoritos">0 favoritos</span>
      </div>
      <div id="favorito-selecionado" class="favorito-selecionado vazio-favorito">
        Marque uma estrela em uma partida para ver todas as informações aqui.
      </div>
      <form id="confirmar-simulacao" class="confirmar-simulacao">
        <div>
          <h4>Confirmar valor</h4>
        </div>
        <div id="resumo-confirmacao" class="resumo-confirmacao">
          Selecione uma partida favorita para conferir os times e as estatísticas.
        </div>
        <div id="mercados-aposta" class="mercados-aposta">
          Selecione uma partida favorita para visualizar os mercados.
        </div>
        <div id="bilhete-aposta" class="bilhete-aposta vazio">
          <span>Nenhum mercado selecionado</span>
        </div>
        <button type="button" id="cancelar-aposta" class="botao-cancelar-aposta" disabled>
          Cancelar aposta
        </button>
        <label for="valor-simulacao">
          Valor em reais
          <input id="valor-simulacao" name="valor" type="number" min="1" max="500" step="0.01" value="50.00" required>
        </label>
        <button type="submit" class="botao-confirmar-dinheiro">Confirmar dinheiro</button>
        <p id="resultado-simulacao" class="resultado-simulacao" role="status" aria-live="polite">
          Nenhum valor confirmado.
        </p>
      </form>
    </section>
    <div class="tabela-rodada">
      <div class="titulo-tabela">
        <div>
          <span class="tag">Brasileirão</span>
          <h3>Tabela do Brasileirão 2026</h3>
        </div>
      </div>
      <div class="tabela-scroll" role="region" aria-label="Tabela completa da Série A" tabindex="0">
        <div class="linha-tabela linha-cabecalho">
          <span>Pos</span><span>Time</span><span>J</span><span>V</span><span>E</span><span>D</span><span>GP</span><span>GC</span><span>SG</span><span>PTS</span>
        </div>
        <?php foreach ($tabelaBrasileirao2026 as $linha): ?>
          <div class="linha-tabela<?= $linha['posicao'] <= 4 ? ' zona-libertadores' : ($linha['posicao'] >= 18 ? ' zona-rebaixamento' : '') ?>">
            <span><?= e($linha['posicao']) ?></span>
            <strong><?= e($linha['time']) ?></strong>
            <span><?= e($linha['jogos']) ?></span>
            <span><?= e($linha['vitorias']) ?></span>
            <span><?= e($linha['empates']) ?></span>
            <span><?= e($linha['derrotas']) ?></span>
            <span><?= e($linha['gols_pro']) ?></span>
            <span><?= e($linha['gols_contra']) ?></span>
            <span><?= e($linha['saldo'] > 0 ? '+' . $linha['saldo'] : $linha['saldo']) ?></span>
            <strong><?= e($linha['pontos']) ?></strong>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="nota-estatisticas">Dados atualizados em 26/11/2026. Fonte: ESPN.</p>
    </div>
  </div>
  <aside class="destaques">
    <span class="tag">Em foco</span>
    <h2>Atletas em destaque</h2>
    <?php foreach (array_slice($jogadores, 0, 12) as $jogador): ?>
      <article class="atleta">
        <div class="numero"><?= e($jogador['numero']) ?></div>
        <div>
          <strong><?= e($jogador['nome']) ?></strong>
          <small><?= e($jogador['time']) ?> · <?= e($jogador['posicao']) ?></small>
        </div>
      </article>
    <?php endforeach; ?>
    <section class="jornal-futebol" aria-labelledby="titulo-jornal-futebol">
      <div class="titulo-jornal">
        <div>
          <span class="tag">Últimas notícias</span>
          <h3 id="titulo-jornal-futebol">Jornal do futebol</h3>
        </div>
        <span class="fonte-jornal">Cobertura internacional</span>
      </div>
      <div class="busca-noticias-mundo">
        <label for="pesquisa-noticias-mundo">Pesquisar tema</label>
        <input id="pesquisa-noticias-mundo" type="search" placeholder="Ex.: seleção, transferências, Barcelona">
      </div>
      <div class="temas-noticias-mundo" aria-label="Temas do jornal">
        <button type="button" class="tema-noticia ativo" data-tema="todos">Todos</button>
        <button type="button" class="tema-noticia" data-tema="selecoes">Seleções</button>
        <button type="button" class="tema-noticia" data-tema="transferencias">Transferências</button>
        <button type="button" class="tema-noticia" data-tema="clubes">Clubes</button>
        <button type="button" class="tema-noticia" data-tema="competicoes">Competições</button>
      </div>
      <div class="lista-noticias-mundo">
        <?php
        $noticiasReais = [
            [
                'titulo' => 'Confronto entre polícia e torcedores interrompe partida da Liga Europa!',
                'link' => 'https://sports.yahoo.com/soccer/articles/police-fans-clash-europa-league-183700655.html',
                'data' => '2026-11-26',
                'fonte' => 'OneFootball',
                'tema' => 'competicoes',
            ],
            [
                'titulo' => 'Hornchurch contrata zagueiro Weaver por empréstimo',
                'link' => 'https://sports.yahoo.com/general/articles/hornchurch-sign-sheff-wed-defender-183646694.html',
                'data' => '2026-11-26',
                'fonte' => 'BBC',
                'tema' => 'transferencias',
            ],
            [
                'titulo' => 'A volta de Magill pode fortalecer a seleção da Irlanda do Norte',
                'link' => 'https://sports.yahoo.com/womens-college-basketball/articles/returning-magill-bring-northern-ireland-183425269.html',
                'data' => '2026-11-26',
                'fonte' => 'BBC',
                'tema' => 'selecoes',
            ],
            [
                'titulo' => 'Amistosos contra a Grécia podem preparar a Irlanda do Norte',
                'link' => 'https://www.bbc.co.uk/sport/football/articles/cw1l0j1je8r2o',
                'data' => '2026-11-26',
                'fonte' => 'BBC',
                'tema' => 'selecoes',
            ],
            [
                'titulo' => 'Bap sugere reduzir o número de times rebaixados no Brasileirão',
                'link' => 'https://sports.yahoo.com/soccer/articles/bap-suggests-reducing-number-teams-183000814.html',
                'data' => '2026-11-26',
                'fonte' => 'OneFootball',
                'tema' => 'clubes',
            ],
            [
                'titulo' => 'Guardiola admite constrangimento por recusar convite para seleção',
                'link' => 'https://sports.yahoo.com/soccer/articles/pep-guardiola-admits-being-ashamed-183000305.html',
                'data' => '2026-11-26',
                'fonte' => 'City Xtra',
                'tema' => 'selecoes',
            ],
            [
                'titulo' => 'Jovem revelação do Barcelona evolui na base após adaptação lenta',
                'link' => 'https://sports.yahoo.com/college-sports/articles/barcelona-teenage-starlet-making-major-182900976.html',
                'data' => '2026-11-26',
                'fonte' => 'Barca Universal',
                'tema' => 'clubes',
            ],
            [
                'titulo' => 'Calvert-Lewin renova contrato com o Leeds até 2029',
                'link' => 'https://sports.yahoo.com/soccer/articles/dominic-calvert-lewin-signs-leeds-182628049.html',
                'data' => '2026-11-26',
                'fonte' => 'The Independent',
                'tema' => 'transferencias',
            ],
            [
                'titulo' => 'Torcedor cai de arquibancada e suspende partida da Liga Europa',
                'link' => 'https://sports.yahoo.com/soccer/articles/fan-falls-stands-europa-league-182600995.html',
                'data' => '2026-11-26',
                'fonte' => 'OneFootball',
                'tema' => 'competicoes',
            ],
            [
                'titulo' => 'Liverpool pode aguardar até o verão para contratar meio-campista',
                'link' => 'https://sports.yahoo.com/soccer/articles/journalist-liverpool-might-wait-until-182000415.html',
                'data' => '2026-11-26',
                'fonte' => 'Anfield Index',
                'tema' => 'transferencias',
            ],
        ];
        ?>
        <?php if ($noticiasReais): ?>
          <?php foreach ($noticiasReais as $noticia): ?>
            <article class="noticia-mundo" data-tema="<?= e($noticia['tema']) ?>" data-titulo="<?= e(strtolower($noticia['titulo'])) ?>">
              <span class="data-noticia-mundo">
                <?php
                $timestamp = strtotime($noticia['data']);
                echo e($timestamp ? date('d/m/Y', $timestamp) : '');
                ?>
              </span>
              <a href="<?= e($noticia['link']) ?>" target="_blank" rel="noopener noreferrer">
                <?= e($noticia['titulo']) ?>
              </a>
              <?php if ($noticia['fonte'] !== ''): ?>
                <small><?= e($noticia['fonte']) ?></small>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="erro-noticias-mundo">Não foi possível atualizar o jornal agora.</p>
        <?php endif; ?>
      </div>
      <button type="button" id="ampliar-noticias-mundo" class="ampliar-noticias-mundo">
        Mostrar mais 3 notícias
      </button>
    </section>
  </aside>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const noticiasMundo = [...document.querySelectorAll('.noticia-mundo')];
  const botoesTema = [...document.querySelectorAll('.tema-noticia')];
  const campoPesquisaNoticias = document.getElementById('pesquisa-noticias-mundo');
  const botaoAmpliarNoticias = document.getElementById('ampliar-noticias-mundo');
  let temaNoticia = 'todos';
  let limiteNoticias = 3;

  const filtrarNoticias = () => {
    const termo = (campoPesquisaNoticias?.value || '').trim().toLowerCase();
    let visiveis = noticiasMundo.filter(noticia => {
      const temaCorresponde = temaNoticia === 'todos' || noticia.dataset.tema === temaNoticia;
      const textoCorresponde = !termo
        || noticia.dataset.titulo.includes(termo)
        || noticia.textContent.toLowerCase().includes(termo);
      return temaCorresponde && textoCorresponde;
    });

    noticiasMundo.forEach(noticia => { noticia.hidden = true; });
    visiveis.slice(0, limiteNoticias).forEach(noticia => { noticia.hidden = false; });

    const restantes = visiveis.length - limiteNoticias;
    botaoAmpliarNoticias.hidden = restantes <= 0;
    botaoAmpliarNoticias.textContent = restantes > 0
      ? `Mostrar mais ${Math.min(3, restantes)} notícias`
      : 'Todas as notícias exibidas';
  };

  botoesTema.forEach(botao => {
    botao.addEventListener('click', () => {
      botoesTema.forEach(item => item.classList.remove('ativo'));
      botao.classList.add('ativo');
      temaNoticia = botao.dataset.tema;
      limiteNoticias = 3;
      filtrarNoticias();
    });
  });

  campoPesquisaNoticias?.addEventListener('input', () => {
    limiteNoticias = 3;
    filtrarNoticias();
  });

  botaoAmpliarNoticias?.addEventListener('click', () => {
    limiteNoticias += 3;
    filtrarNoticias();
  });

  filtrarNoticias();

  const favoritos = new Set(JSON.parse(localStorage.getItem('futnex_favoritos') || '[]'));
  const cards = [...document.querySelectorAll('.card-jogo')];
  const botaoAlternarResultados = document.getElementById('alternar-resultados');
  const listaResultados = document.getElementById('lista-resultados');
  if (botaoAlternarResultados && listaResultados) {
    botaoAlternarResultados.addEventListener('click', () => {
      const aberto = botaoAlternarResultados.getAttribute('aria-expanded') === 'true';
      botaoAlternarResultados.setAttribute('aria-expanded', String(!aberto));
      botaoAlternarResultados.textContent = aberto ? 'Mostrar jogos' : 'Ocultar jogos';
      listaResultados.hidden = aberto;
    });
  }
  document.querySelectorAll('.detalhes-resultado').forEach(botao => {
    botao.addEventListener('click', () => {
      const painel = botao.nextElementSibling;
      const aberto = botao.getAttribute('aria-expanded') === 'true';
      botao.setAttribute('aria-expanded', String(!aberto));
      botao.textContent = aberto ? 'Ver estatísticas' : 'Ocultar estatísticas';
      painel.hidden = aberto;
    });
  });
  const areaFavoritos = document.getElementById('favoritos-partidas');
  const favoritoSelecionado = document.getElementById('favorito-selecionado');
  const quantidadeFavoritos = document.getElementById('quantidade-favoritos');
  const formularioSimulacao = document.getElementById('confirmar-simulacao');
  const resultadoSimulacao = document.getElementById('resultado-simulacao');
  const resumoConfirmacao = document.getElementById('resumo-confirmacao');
  const mercadosAposta = document.getElementById('mercados-aposta');
  const bilheteAposta = document.getElementById('bilhete-aposta');
  const botaoCancelarAposta = document.getElementById('cancelar-aposta');
  let mercadoSelecionado = null;
  const notificacao = document.createElement('div');
  notificacao.className = 'confirmacao-favorito';
  notificacao.setAttribute('role', 'status');
  notificacao.setAttribute('aria-live', 'polite');
  document.body.appendChild(notificacao);

  let tempoNotificacao;
  const mostrarConfirmacao = (mensagem) => {
    notificacao.textContent = mensagem;
    notificacao.classList.add('visivel');
    clearTimeout(tempoNotificacao);
    tempoNotificacao = setTimeout(() => notificacao.classList.remove('visivel'), 2800);
  };

  const mostrarFavoritoSelecionado = (card) => {
    if (!card) {
      favoritoSelecionado.className = 'favorito-selecionado vazio-favorito';
      favoritoSelecionado.textContent = 'Marque uma estrela em uma partida para ver todas as informações aqui.';
      return;
    }

    favoritoSelecionado.className = 'favorito-selecionado';
    favoritoSelecionado.replaceChildren();

    const confronto = document.createElement('div');
    confronto.className = 'favorito-confronto';

    const horario = document.createElement('span');
    horario.className = 'hora-favorito';
    horario.textContent = card.dataset.hora;

    const nomes = document.createElement('h4');
    nomes.textContent = `${card.dataset.casa} × ${card.dataset.fora}`;

    const resumo = document.createElement('p');
    resumo.textContent = card.dataset.resumo;

    const dados = document.createElement('div');
    dados.className = 'dados-favorito';
    [
      ['Campeonato', card.dataset.campeonato],
      ['Estádio', card.dataset.estadio],
      ['Clima', card.dataset.clima],
      ['Transmissão', card.dataset.transmissao],
    ].forEach(([rotulo, valor]) => {
      const item = document.createElement('div');
      const titulo = document.createElement('span');
      titulo.textContent = rotulo;
      const conteudo = document.createElement('strong');
      conteudo.textContent = valor;
      item.append(titulo, conteudo);
      dados.appendChild(item);
    });

    const estatisticas = JSON.parse(card.dataset.estatisticas || '{}');
    const blocoEstatisticas = document.createElement('div');
    blocoEstatisticas.className = 'estatisticas-completas';
    const tituloEstatisticas = document.createElement('h4');
    tituloEstatisticas.textContent = 'Estatísticas completas';
    blocoEstatisticas.appendChild(tituloEstatisticas);

    Object.entries(estatisticas).forEach(([rotulo, valores]) => {
      const linha = document.createElement('div');
      linha.className = 'estatistica-completa';
      const casa = document.createElement('strong');
      casa.textContent = valores[0];
      const nome = document.createElement('span');
      nome.textContent = rotulo;
      const fora = document.createElement('strong');
      fora.textContent = valores[1];
      linha.append(casa, nome, fora);
      blocoEstatisticas.appendChild(linha);
    });

    const escalacoes = document.createElement('div');
    escalacoes.className = 'escalacoes-completas';
    const montarEscalacao = (time, jogadoresJson) => {
      const coluna = document.createElement('div');
      const titulo = document.createElement('h4');
      titulo.textContent = time;
      const lista = document.createElement('ol');
      const jogadores = JSON.parse(jogadoresJson || '[]');
      jogadores.forEach((jogador, indice) => {
        const item = document.createElement('li');
        item.textContent = jogador;
        if (jogador.startsWith('Reserva:')) item.className = 'jogador-reserva';
        else item.dataset.posicao = String(indice + 1);
        lista.appendChild(item);
      });
      coluna.append(titulo, lista);
      return coluna;
    };
    escalacoes.append(
      montarEscalacao(card.dataset.casa, card.dataset.escalaCasa),
      montarEscalacao(card.dataset.fora, card.dataset.escalaFora)
    );

    confronto.append(horario, nomes);
    favoritoSelecionado.append(confronto, resumo, dados, blocoEstatisticas, escalacoes);
  };

  const atualizarBilhete = (card) => {
    const stake = Number(document.getElementById('valor-simulacao').value) || 0;
    if (!mercadoSelecionado) {
      bilheteAposta.className = 'bilhete-aposta vazio';
      bilheteAposta.innerHTML = '<span>Nenhum mercado selecionado</span>';
      return;
    }
    const retorno = stake * mercadoSelecionado.cotacao;
    bilheteAposta.className = 'bilhete-aposta';
    bilheteAposta.innerHTML = `
      <div class="bilhete-cabecalho"><strong>Bilhete único</strong><span>1 seleção</span></div>
      <div class="bilhete-selecao">
        <span>${card.dataset.casa} × ${card.dataset.fora}</span>
        <strong>${mercadoSelecionado.nome}</strong>
        <b>${mercadoSelecionado.cotacao.toFixed(2)}</b>
      </div>
      <div class="bilhete-valores">
        <span>Valor <strong id="bilhete-stake">${stake.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</strong></span>
        <span>Retorno potencial <strong id="bilhete-retorno">${retorno.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</strong></span>
      </div>`;
  };

  const atualizarAreaFavoritos = (cardSelecionado = null) => {
    const total = favoritos.size;
    quantidadeFavoritos.textContent = `${total} ${total === 1 ? 'favorito' : 'favoritos'}`;
    const cardParaMostrar = cardSelecionado && favoritos.has(cardSelecionado.dataset.id)
      ? cardSelecionado
      : cards.find(card => favoritos.has(card.dataset.id)) || null;
    mostrarFavoritoSelecionado(cardParaMostrar);

    if (!cardParaMostrar) {
      resumoConfirmacao.className = 'resumo-confirmacao vazio';
      resumoConfirmacao.textContent = 'Selecione uma partida favorita para conferir os times e as estatísticas.';
      mercadosAposta.textContent = 'Selecione uma partida favorita para visualizar os mercados.';
      mercadoSelecionado = null;
      botaoCancelarAposta.disabled = true;
      bilheteAposta.className = 'bilhete-aposta vazio';
      bilheteAposta.innerHTML = '<span>Nenhum mercado selecionado</span>';
      return;
    }

    mercadoSelecionado = null;
    botaoCancelarAposta.disabled = true;
    bilheteAposta.className = 'bilhete-aposta vazio';
    bilheteAposta.innerHTML = '<span>Nenhum mercado selecionado</span>';

    const estatisticas = JSON.parse(cardParaMostrar.dataset.estatisticas || '{}');
    resumoConfirmacao.className = 'resumo-confirmacao';
    resumoConfirmacao.replaceChildren();

    const confronto = document.createElement('div');
    confronto.className = 'confronto-confirmacao';
    const timeCasa = document.createElement('strong');
    timeCasa.textContent = cardParaMostrar.dataset.casa;
    const versus = document.createElement('span');
    versus.textContent = '×';
    const timeFora = document.createElement('strong');
    timeFora.textContent = cardParaMostrar.dataset.fora;
    confronto.append(timeCasa, versus, timeFora);

    const resumoStats = document.createElement('div');
    resumoStats.className = 'estatisticas-confirmacao';
    ['Posse de bola', 'Finalizações', 'Escanteios'].forEach((rotulo) => {
      if (!estatisticas[rotulo]) return;
      const item = document.createElement('div');
      const nome = document.createElement('span');
      nome.textContent = rotulo;
      const valores = document.createElement('strong');
      valores.textContent = `${estatisticas[rotulo][0]}  |  ${estatisticas[rotulo][1]}`;
      item.append(nome, valores);
      resumoStats.appendChild(item);
    });

    resumoConfirmacao.append(confronto, resumoStats);

    mercadosAposta.replaceChildren();
    const mercados = [
      { nome: `${cardParaMostrar.dataset.casa} vence`, cotacao: 2.10, tipo: 'Resultado' },
      { nome: 'Empate', cotacao: 3.25, tipo: 'Resultado' },
      { nome: `${cardParaMostrar.dataset.fora} vence`, cotacao: 2.75, tipo: 'Resultado' },
      { nome: 'Mais de 2.5 gols', cotacao: 1.85, tipo: 'Gols' },
      { nome: 'Ambas marcam', cotacao: 1.70, tipo: 'Gols' },
      { nome: 'Dupla chance: casa ou empate', cotacao: 1.45, tipo: 'Dupla chance' },
    ];
    mercados.forEach((mercado) => {
      const botao = document.createElement('button');
      botao.type = 'button';
      botao.className = 'mercado-aposta';
      botao.dataset.nome = mercado.nome;
      botao.dataset.cotacao = String(mercado.cotacao);
      botao.innerHTML = `<small>${mercado.tipo}</small><span>${mercado.nome}</span><strong>${mercado.cotacao.toFixed(2)}</strong>`;
      botao.addEventListener('click', () => {
        mercadosAposta.querySelectorAll('.mercado-aposta').forEach(item => item.classList.remove('selecionado'));
        botao.classList.add('selecionado');
        mercadoSelecionado = mercado;
        botaoCancelarAposta.disabled = false;
        atualizarBilhete(cardParaMostrar);
      });
      mercadosAposta.appendChild(botao);
    });
  };

  atualizarAreaFavoritos();

  botaoCancelarAposta.addEventListener('click', () => {
    mercadoSelecionado = null;
    mercadosAposta.querySelectorAll('.mercado-aposta').forEach(item => item.classList.remove('selecionado'));
    botaoCancelarAposta.disabled = true;
    bilheteAposta.className = 'bilhete-aposta vazio';
    bilheteAposta.innerHTML = '<span>Nenhum mercado selecionado</span>';
    resultadoSimulacao.textContent = 'Aposta cancelada.';
    resultadoSimulacao.className = 'resultado-simulacao erro';
  });

  document.getElementById('valor-simulacao').addEventListener('input', () => {
    const partidaAtual = cards.find(card => favoritos.has(card.dataset.id));
    if (partidaAtual) atualizarBilhete(partidaAtual);
  });

  formularioSimulacao.addEventListener('submit', (evento) => {
    evento.preventDefault();
    const valor = Number(new FormData(formularioSimulacao).get('valor'));
    if (!Number.isFinite(valor) || valor <= 0) {
      resultadoSimulacao.textContent = 'Informe um valor válido maior que R$ 0,00.';
      resultadoSimulacao.className = 'resultado-simulacao erro';
      return;
    }
    if (valor > 500) {
      resultadoSimulacao.textContent = 'O limite demonstrativo desta ação é R$ 500,00.';
      resultadoSimulacao.className = 'resultado-simulacao erro';
      return;
    }

    const partidaAtual = cards.find(card => favoritos.has(card.dataset.id));
    const partida = partidaAtual
      ? `${partidaAtual.dataset.casa} × ${partidaAtual.dataset.fora}`
      : 'Nenhuma partida selecionada';
    if (!partidaAtual || !mercadoSelecionado) {
      resultadoSimulacao.textContent = 'Selecione uma partida favorita e um mercado.';
      resultadoSimulacao.className = 'resultado-simulacao erro';
      return;
    }
    const valorFormatado = valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    const retorno = valor * mercadoSelecionado.cotacao;
    const retornoFormatado = retorno.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    resultadoSimulacao.textContent = `Confirmação registrada: ${valorFormatado} · ${mercadoSelecionado.nome} · ${partida} · Retorno potencial ${retornoFormatado}`;
    resultadoSimulacao.className = 'resultado-simulacao sucesso';
  });

  cards.forEach(card => {
    const id = card.dataset.id;
    const favorito = card.querySelector('.favorito-jogo');
    const detalhes = card.querySelector('.detalhes-jogo');
    const painel = card.querySelector('.painel-jogo');

    if (favoritos.has(id)) {
      favorito.classList.add('ativo');
      favorito.textContent = '★';
    }

    favorito.addEventListener('click', () => {
      if (favoritos.has(id)) favoritos.delete(id);
      else favoritos.add(id);
      localStorage.setItem('futnex_favoritos', JSON.stringify([...favoritos]));
      const selecionado = favoritos.has(id);
      favorito.classList.toggle('ativo', selecionado);
      favorito.textContent = selecionado ? '★' : '☆';
      mostrarConfirmacao(
        selecionado
          ? `★ ${card.dataset.casa} × ${card.dataset.fora} adicionada aos favoritos`
          : `☆ ${card.dataset.casa} × ${card.dataset.fora} removida dos favoritos`
      );
      atualizarAreaFavoritos(selecionado ? card : null);
      if (selecionado) {
        areaFavoritos.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    detalhes.addEventListener('click', () => {
      const aberto = detalhes.getAttribute('aria-expanded') === 'true';
      detalhes.setAttribute('aria-expanded', String(!aberto));
      detalhes.textContent = aberto ? 'Ver detalhes' : 'Ocultar detalhes';
      painel.hidden = aberto;
    });

    card.querySelectorAll('.aba-jogo').forEach(aba => {
      aba.addEventListener('click', () => {
        card.querySelectorAll('.aba-jogo').forEach(item => item.classList.remove('ativa'));
        card.querySelectorAll('.conteudo-aba').forEach(item => item.classList.remove('ativo'));
        aba.classList.add('ativa');
        card.querySelector(`[data-conteudo="${aba.dataset.aba}"]`).classList.add('ativo');
      });
    });
  });

  document.querySelectorAll('.filtro-jogo').forEach(botao => {
    botao.addEventListener('click', () => {
      document.querySelectorAll('.filtro-jogo').forEach(item => item.classList.remove('ativo'));
      botao.classList.add('ativo');
      cards.forEach(card => {
        const filtro = botao.dataset.filtro;
        const corresponde = filtro === 'todos'
          || card.dataset.status === filtro
          || (filtro === 'favoritos' && favoritos.has(card.dataset.id));
        card.hidden = !corresponde;
      });
    });
  });
});
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
