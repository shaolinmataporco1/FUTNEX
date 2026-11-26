<?php
require __DIR__ . '/config.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');
    $stmt = $pdo->prepare('SELECT id,nome,email,senha,nivel FROM usuarios WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        unset($usuario['senha']);
        $usuario['id'] = (int) $usuario['id'];
        $_SESSION['usuario'] = $usuario;
        setcookie('futnex_visitou', 'sim', [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        header('Location: /');
        exit;
    }
    $erro = 'E-mail ou senha incorretos.';
}
$pagina = 'Entrar';
require __DIR__ . '/partials/header.php';
?>
<section class="secao">
  <form class="form-admin auth-form" method="post">
    <span class="tag">Bem-vindo de volta</span>
    <h1>Entrar</h1>
    <?php if ($erro !== ''): ?><p class="mensagem erro"><?= e($erro) ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label>E-mail<input type="email" name="email" maxlength="150" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>"></label>
    <label>Senha<input type="password" name="senha" required autocomplete="current-password"></label>
    <button class="botao" type="submit">Entrar</button>
    <p>Não tem conta? <a href="/cadastro.php">Cadastre-se</a>.</p>
  </form>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
