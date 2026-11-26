<?php
require __DIR__ . '/config.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');
    $confirmacao = (string) ($_POST['confirmacao'] ?? '');

    $nomeCaracteres = preg_match_all('/./us', $nome, $correspondencias);
    if ($nome === '' || $nomeCaracteres === false || $nomeCaracteres > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
        $erro = 'Informe um nome, e-mail válido e senha com pelo menos 8 caracteres.';
    } elseif ($senha !== $confirmacao) {
        $erro = 'As senhas não correspondem.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome,email,senha) VALUES (?,?,?)');
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => (int) $pdo->lastInsertId(),
                'nome' => $nome,
                'email' => $email,
                'nivel' => 'usuario',
            ];
            header('Location: /');
            exit;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $erro = 'Este e-mail já possui uma conta.';
            } else {
                throw $exception;
            }
        }
    }
}
$pagina = 'Cadastro';
require __DIR__ . '/partials/header.php';
?>
<section class="secao">
  <form class="form-admin auth-form" method="post">
    <span class="tag">Junte-se ao FUTNEX</span>
    <h1>Criar conta</h1>
    <?php if ($erro !== ''): ?><p class="mensagem erro"><?= e($erro) ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <label>Nome<input name="nome" maxlength="100" required autocomplete="name" value="<?= e($_POST['nome'] ?? '') ?>"></label>
    <label>E-mail<input type="email" name="email" maxlength="150" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>"></label>
    <label>Senha (mínimo 8 caracteres)<input type="password" name="senha" minlength="8" required autocomplete="new-password"></label>
    <label>Confirme a senha<input type="password" name="confirmacao" minlength="8" required autocomplete="new-password"></label>
    <button class="botao" type="submit">Cadastrar</button>
    <p>Já tem conta? <a href="/login.php">Entrar</a>.</p>
  </form>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
