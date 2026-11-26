<?php
require __DIR__ . '/config.php';
$pagina = 'Noticias';
require __DIR__ . '/partials/header.php';
?>
<section class="secao">
  <div class="titulo-secao"><div><span class="tag">Cobertura completa</span><h1>Notícias</h1></div></div>
  <form class="busca-form" method="get">
    <label for="busca-noticias">Buscar por título ou resumo</label>
    <input id="busca-noticias" name="busca" value="<?= e($_GET['busca'] ?? '') ?>" placeholder="Ex.: Brasileirão">
    <label for="categoria-noticias">Campeonato
      <select id="categoria-noticias" name="categoria">
        <option value="">Todos os campeonatos</option>
        <?php foreach ($categoriasNoticias as $categoria): ?>
          <option value="<?= e($categoria) ?>" <?= $categoriaFiltro === $categoria ? 'selected' : '' ?>><?= e($categoria) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="botao" type="submit">Buscar</button>
    <?php if (!empty($_GET['busca']) || !empty($_GET['categoria'])): ?><a href="/noticias.php">Limpar</a><?php endif; ?>
  </form>
  <div class="grade-noticias">
    <?php foreach ($noticias as $noticia): ?>
      <article class="card">
        <img src="<?= e($noticia['imagem']) ?>" alt="<?= e($noticia['titulo']) ?>">
        <div class="card-corpo">
          <span class="categoria"><?= e($noticia['categoria']) ?></span>
          <h3><?= e($noticia['titulo']) ?></h3>
          <p><?= e($noticia['resumo']) ?></p>
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (!$noticias): ?><p class="vazio">Nenhuma notícia encontrada.</p><?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
