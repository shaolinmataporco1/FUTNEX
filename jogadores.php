<?php
require __DIR__ . '/config.php';
$timesComJogadores = $pdo->query(
    'SELECT t.id, t.nome
     FROM times t
     WHERE EXISTS (SELECT 1 FROM jogadores j WHERE j.time_id=t.id)
        OR EXISTS (SELECT 1 FROM jogador_carreira jc WHERE jc.time_id=t.id)
     ORDER BY t.nome'
)->fetchAll();
$pagina = 'Jogadores';
require __DIR__ . '/partials/header.php';
?>
<section class="secao">
  <div class="titulo-secao"><div><span class="tag">Elenco</span><h1>Jogadores</h1></div></div>
  <p class="nota-dados">Os vínculos por time podem ser históricos e não representam necessariamente o elenco atual. Anos não cadastrados e valores de mercado devem ser confirmados e atualizados no painel.</p>
  <form class="busca-form" method="get">
    <label for="filtro-time">Filtrar jogadores por time</label>
    <select id="filtro-time" name="time_id">
      <option value="">Todos os times</option>
      <?php foreach ($timesComJogadores as $time): ?>
        <option value="<?= (int) $time['id'] ?>" <?= (string) ($_GET['time_id'] ?? '') === (string) $time['id'] ? 'selected' : '' ?>><?= e($time['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="botao" type="submit">Filtrar</button>
    <?php if (!empty($_GET['time_id'])): ?><a href="/jogadores.php">Limpar</a><?php endif; ?>
  </form>
  <div class="grade-jogadores">
    <?php foreach ($jogadores as $jogador): ?>
      <article class="card-jogador">
      <?php if ($jogador['numero'] !== null): ?><span class="camisa"><?= e($jogador['numero']) ?></span><?php endif; ?>
        <h3><?= e($jogador['nome']) ?></h3>
      <?php if (!empty($jogador['posicao'])): ?><p><?= e($jogador['posicao']) ?></p><?php endif; ?>
      <small>Clube atual: <?= e($jogador['time'] ?? 'Não cadastrado') ?></small>
        <?php if (!empty($historicosClubes[(int) $jogador['id']])): ?>
          <div class="historico-clubes">
            <strong>Clubes da carreira</strong>
            <ol>
              <?php foreach ($historicosClubes[(int) $jogador['id']] as $passagem): ?>
                <li>
                  <?= e($passagem['time']) ?>
                  <?php if ($passagem['ano_inicio']): ?>
                    <small>(<?= (int) $passagem['ano_inicio'] ?>–<?= $passagem['ano_fim'] ? (int) $passagem['ano_fim'] : 'atual' ?>)</small>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>
          </div>
        <?php endif; ?>
        <?php if ($jogador['valor_mercado'] !== null): ?>
          <strong class="valor-mercado">Estimativa: R$ <?= number_format((float) $jogador['valor_mercado'], 0, ',', '.') ?></strong>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
    <?php if (!$jogadores): ?><p class="vazio">Nenhum jogador encontrado.</p><?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
