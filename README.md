# FUTNEX

Portal de futebol feito com PHP, CSS próprio, MySQL e PDO. Inclui cadastro/login, sessão de usuário, perfis de usuário e administrador, CRUD de notícias/times/jogadores e preferência de tema salva em cookie.

## Requisitos

- PHP 8.1+ com `pdo_mysql`
- MySQL 8+

## Preparar o banco

No PowerShell, na pasta do projeto:

```powershell
& 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe' --default-character-set=utf8mb4 -u root -p -e 'source C:\Users\nathan.0976\Downloads\FUTNEX_completo\FUTNEX\banco\site.sql'
```

O MySQL solicitará a senha no terminal. O script cria o banco `futnex_site`, as tabelas e registros de exemplo, incluindo times de cidades brasileiras e internacionais, notícias de campeonatos e jogadores com histórico de clubes. O catálogo associa jogadores históricos aos clubes; isso não significa que façam parte dos elencos atuais. Anos de passagem e valores não confirmados ficam em branco para edição no painel. Valores preenchidos são estimativas demonstrativas em reais. O script pode ser executado novamente sem duplicar os registros iniciais.

## Criar o administrador

Defina as variáveis de ambiente e execute o utilitário, sem colocar a senha no código-fonte:

```powershell
$env:FUTNEX_DB_PASSWORD = 'SUA_SENHA_MYSQL'
$env:FUTNEX_ADMIN_EMAIL = 'admin@exemplo.com'
$env:FUTNEX_ADMIN_PASSWORD = 'UMA_SENHA_FORTE_COM_12_OU_MAIS_CARACTERES'
php .\banco\criar_admin.php
```

O utilitário é somente CLI e guarda a senha usando `password_hash()`. Usuários que se cadastram pelo site recebem o perfil comum; apenas administradores podem criar, editar e excluir conteúdo.

## Executar

```powershell
$env:FUTNEX_DB_PASSWORD = 'SUA_SENHA_MYSQL'
php -S localhost:8000 -t .
```

Acesse `http://localhost:8000`. Entre em `/cadastro.php` para criar uma conta de usuário, ou em `/login.php` com a conta administrativa para acessar o painel.
