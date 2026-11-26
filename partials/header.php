<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="FUTNEX — notícias, times e jogadores de futebol">
  <title><?= e($pagina ?? 'FUTNEX') ?> | FUTNEX</title>
  <link rel="stylesheet" href="/css/style.css">
</head>
<body class="<?= ($_COOKIE['futnex_tema'] ?? '') === 'escuro' ? 'tema-escuro' : '' ?>">
<header class="topo">
  <a class="logo" href="/"><span>FUT</span>NEX</a>
  <nav aria-label="Navegação principal">
    <a href="/"<?= ($pagina ?? '') === 'Inicio' ? ' class="ativo"' : '' ?>>Início</a>
    <a href="/noticias.php"<?= ($pagina ?? '') === 'Noticias' ? ' class="ativo"' : '' ?>>Notícias</a>
    <a href="/times.php"<?= ($pagina ?? '') === 'Times' ? ' class="ativo"' : '' ?>>Times</a>
    <a href="/jogadores.php"<?= ($pagina ?? '') === 'Jogadores' ? ' class="ativo"' : '' ?>>Jogadores</a>
    <?php if (($_SESSION['usuario']['nivel'] ?? '') === 'admin'): ?>
      <a href="/admin.php"<?= ($pagina ?? '') === 'Admin' ? ' class="ativo"' : '' ?>>Admin</a>
    <?php endif; ?>
    <?php if (!empty($_SESSION['usuario'])): ?>
      <span class="nav-usuario"><?= e($_SESSION['usuario']['nome']) ?></span>
      <form class="nav-form" action="/logout.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <button type="submit">Sair</button>
      </form>
    <?php else: ?>
      <a href="/login.php">Entrar</a>
      <a href="/cadastro.php">Cadastrar</a>
    <?php endif; ?>
  </nav>
</header>
<main>
