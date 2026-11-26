<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/../config.php';
$email = trim((string) getenv('FUTNEX_ADMIN_EMAIL'));
$password = (string) getenv('FUTNEX_ADMIN_PASSWORD');
$name = trim((string) (getenv('FUTNEX_ADMIN_NAME') ?: 'Administrador'));

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12 || $name === '') {
    fwrite(STDERR, "Configure FUTNEX_ADMIN_EMAIL, FUTNEX_ADMIN_PASSWORD (12+ caracteres) e FUTNEX_ADMIN_NAME.\n");
    exit(1);
}

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email=?');
$stmt->execute([$email]);
$userId = $stmt->fetchColumn();
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

if ($userId) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome=?,senha=?,nivel='admin' WHERE id=?");
    $stmt->execute([$name, $passwordHash, $userId]);
} else {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome,email,senha,nivel) VALUES (?,?,?,'admin')");
    $stmt->execute([$name, $email, $passwordHash]);
}
fwrite(STDOUT, "Conta administrativa configurada para {$email}.\n");
