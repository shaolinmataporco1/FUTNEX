<?php
require __DIR__ . '/config.php';

$erro = '';
$acaoPost = '';
$sucesso = $_SESSION['admin_flash'] ?? '';
unset($_SESSION['admin_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validarCsrf();
    $acao = (string) ($_POST['acao'] ?? '');
    $acaoPost = $acao;

    if ($acao === 'selecionar') {
        // The entity selector submits only to reveal the matching form.
    } elseif (($_SESSION['usuario']['nivel'] ?? '') === 'admin') {
        $entidade = (string) ($_POST['entidade'] ?? '');
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

        try {
            if ($acao === 'excluir' && $id && in_array($entidade, ['noticia', 'time', 'jogador'], true)) {
                $tabelas = ['noticia' => 'noticias', 'time' => 'times', 'jogador' => 'jogadores'];
                $stmt = $pdo->prepare("DELETE FROM {$tabelas[$entidade]} WHERE id=?");
                $stmt->execute([$id]);
                $_SESSION['admin_flash'] = $stmt->rowCount() ? 'Registro excluído.' : 'Registro não encontrado.';
            } elseif (in_array($acao, ['criar', 'atualizar'], true) && in_array($entidade, ['noticia', 'time', 'jogador'], true)) {
                $isUpdate = $acao === 'atualizar';
                if ($isUpdate && !$id) {
                    throw new InvalidArgumentException('Registro inválido para edição.');
                }

                if ($entidade === 'noticia') {
                    $valores = [
                        trim((string) ($_POST['categoria'] ?? '')),
                        trim((string) ($_POST['titulo'] ?? '')),
                        trim((string) ($_POST['resumo'] ?? '')),
                        trim((string) ($_POST['conteudo'] ?? '')),
                        trim((string) ($_POST['imagem'] ?? '')),
                    ];
                    if ($valores[0] === '' || $valores[1] === '' || $valores[2] === '' || $valores[3] === '') {
                        throw new InvalidArgumentException('Preencha categoria, título, resumo e conteúdo.');
                    }
                    $sql = $isUpdate
                        ? 'UPDATE noticias SET categoria=?,titulo=?,resumo=?,conteudo=?,imagem=? WHERE id=?'
                        : 'INSERT INTO noticias (categoria,titulo,resumo,conteudo,imagem) VALUES (?,?,?,?,?)';
                } elseif ($entidade === 'time') {
                    $valores = [
                        trim((string) ($_POST['nome'] ?? '')),
                        trim((string) ($_POST['cidade'] ?? '')) ?: null,
                        strtoupper(trim((string) ($_POST['estado'] ?? ''))) ?: null,
                        ($_POST['fundacao'] ?? '') === '' ? null : filter_var($_POST['fundacao'], FILTER_VALIDATE_INT),
                    ];
                    if ($valores[0] === '' || ($valores[3] !== null && ($valores[3] === false || $valores[3] < 1 || $valores[3] > 2100))) {
                        throw new InvalidArgumentException('Informe um nome e um ano de fundação válido.');
                    }
                    if ($valores[2] !== null && !preg_match('/^[A-Z]{2}$/', $valores[2])) {
                        throw new InvalidArgumentException('A sigla do estado deve ter duas letras.');
                    }
                    $sql = $isUpdate
                        ? 'UPDATE times SET nome=?,cidade=?,estado=?,fundacao=? WHERE id=?'
                        : 'INSERT INTO times (nome,cidade,estado,fundacao) VALUES (?,?,?,?)';
                } else {
                    $numero = ($_POST['numero'] ?? '') === '' ? null : filter_var($_POST['numero'], FILTER_VALIDATE_INT);
                    $timeId = ($_POST['time_id'] ?? '') === '' ? null : filter_var($_POST['time_id'], FILTER_VALIDATE_INT);
                    $valorMercado = ($_POST['valor_mercado'] ?? '') === '' ? null : filter_var($_POST['valor_mercado'], FILTER_VALIDATE_FLOAT);
                    $carreiraInput = $_POST['carreira'] ?? [];
                    if (!is_array($carreiraInput)) {
                        throw new InvalidArgumentException('Selecione clubes válidos para o histórico.');
                    }
                    $carreiraIds = [];
                    foreach ($carreiraInput as $clubeIdInput) {
                        $clubeId = filter_var($clubeIdInput, FILTER_VALIDATE_INT);
                        if ($clubeId === false || $clubeId < 1) {
                            throw new InvalidArgumentException('Há um clube inválido no histórico.');
                        }
                        $carreiraIds[] = (int) $clubeId;
                    }
                    $carreiraIds = array_values(array_unique($carreiraIds));
                    $valores = [
                        trim((string) ($_POST['nome'] ?? '')),
                        $numero,
                        trim((string) ($_POST['nacionalidade'] ?? '')) ?: null,
                        trim((string) ($_POST['posicao'] ?? '')) ?: null,
                        $timeId,
                        $valorMercado,
                    ];
                    if ($valores[0] === '' || ($numero !== null && ($numero === false || $numero < 0 || $numero > 99)) || ($timeId !== null && $timeId === false) || ($valorMercado !== null && ($valorMercado === false || $valorMercado < 0 || $valorMercado > 9999999999999.99))) {
                        throw new InvalidArgumentException('Confira o nome, o número, o valor de mercado e o time selecionado.');
                    }
                    if ($timeId !== null) {
                        $checkTeam = $pdo->prepare('SELECT 1 FROM times WHERE id=?');
                        $checkTeam->execute([$timeId]);
                        if (!$checkTeam->fetchColumn()) {
                            throw new InvalidArgumentException('O time selecionado não existe.');
                        }
                    }
                    if ($timeId !== null && !in_array((int) $timeId, $carreiraIds, true)) {
                        array_unshift($carreiraIds, (int) $timeId);
                    }
                    if ($carreiraIds) {
                        $placeholders = implode(',', array_fill(0, count($carreiraIds), '?'));
                        $checkTeams = $pdo->prepare("SELECT COUNT(*) FROM times WHERE id IN ({$placeholders})");
                        $checkTeams->execute($carreiraIds);
                        if ((int) $checkTeams->fetchColumn() !== count($carreiraIds)) {
                            throw new InvalidArgumentException('Um ou mais clubes selecionados não existem.');
                        }
                    }
                    $sql = $isUpdate
                        ? 'UPDATE jogadores SET nome=?,numero=?,nacionalidade=?,posicao=?,time_id=?,valor_mercado=? WHERE id=?'
                        : 'INSERT INTO jogadores (nome,numero,nacionalidade,posicao,time_id,valor_mercado) VALUES (?,?,?,?,?,?)';
                }

                if ($isUpdate) {
                    $valores[] = $id;
                }
                $carreiraExistente = [];
                if ($entidade === 'jogador') {
                    if ($isUpdate) {
                        $stmtCarreira = $pdo->prepare('SELECT time_id,ano_inicio,ano_fim FROM jogador_carreira WHERE jogador_id=?');
                        $stmtCarreira->execute([$id]);
                        foreach ($stmtCarreira->fetchAll() as $passagem) {
                            $carreiraExistente[(int) $passagem['time_id']] = [$passagem['ano_inicio'], $passagem['ano_fim']];
                        }
                    }
                    $pdo->beginTransaction();
                }
                $stmt = $pdo->prepare($sql);
                $stmt->execute($valores);
                if ($entidade === 'jogador') {
                    $jogadorId = $isUpdate ? (int) $id : (int) $pdo->lastInsertId();
                    $pdo->prepare('DELETE FROM jogador_carreira WHERE jogador_id=?')->execute([$jogadorId]);
                    $stmtCarreira = $pdo->prepare(
                        'INSERT INTO jogador_carreira (jogador_id,time_id,ano_inicio,ano_fim) VALUES (?,?,?,?)'
                    );
                    foreach ($carreiraIds as $clubeId) {
                        $periodo = $carreiraExistente[$clubeId] ?? [null, null];
                        $stmtCarreira->execute([$jogadorId, $clubeId, $periodo[0], $periodo[1]]);
                    }
                    $pdo->commit();
                }
                $_SESSION['admin_flash'] = $isUpdate ? 'Registro atualizado.' : 'Registro cadastrado.';
            } else {
                throw new InvalidArgumentException('Ação inválida.');
            }
        } catch (InvalidArgumentException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $erro = $exception->getMessage();
        } catch (PDOException $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($exception->getCode() === '23000') {
                $erro = 'Não foi possível salvar: confira campos únicos e vínculos com outros registros.';
            } else {
                throw $exception;
            }
        }

        if ($erro === '') {
            header('Location: /admin.php');
            exit;
        }
    }
}

$autenticado = ($_SESSION['usuario']['nivel'] ?? '') === 'admin';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acaoPost !== 'selecionar' && !$autenticado) {
    http_response_code(403);
    $erro = 'Somente administradores podem alterar o conteúdo.';
}
$pagina = 'Admin';
$editEntidade = (string) ($_GET['entidade'] ?? '');
$editId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$registro = null;
if ($autenticado && $editId && in_array($editEntidade, ['noticia', 'time', 'jogador'], true)) {
    $tabelas = ['noticia' => 'noticias', 'time' => 'times', 'jogador' => 'jogadores'];
    $stmt = $pdo->prepare("SELECT * FROM {$tabelas[$editEntidade]} WHERE id=?");
    $stmt->execute([$editId]);
    $registro = $stmt->fetch() ?: null;
    if (!$registro) {
        $erro = 'Registro não encontrado para edição.';
    }
}

require __DIR__ . '/partials/header.php';
?>
<section class="secao admin-area">
  <div class="titulo-secao">
    <div><span class="tag">Gerenciamento</span><h1>Painel administrativo</h1></div>
  </div>

  <?php if ($erro !== ''): ?><p class="mensagem erro"><?= e($erro) ?></p><?php endif; ?>
  <?php if ($sucesso !== ''): ?><p class="mensagem sucesso"><?= e($sucesso) ?></p><?php endif; ?>

  <?php if (!$autenticado): ?>
    <div class="form-admin login-admin">
      <h2>Acesso restrito</h2>
      <p>Entre com uma conta de administrador para gerenciar o conteúdo.</p>
      <a class="botao" href="/login.php">Fazer login</a>
    </div>
  <?php else: ?>
    <?php
    $formEntidade = ($erro !== '' || $acaoPost === 'selecionar') ? (string) ($_POST['entidade'] ?? '') : $editEntidade;
    $formRegistro = $erro !== '' ? $_POST : ($registro ?? []);
    $formId = $erro !== '' ? (int) ($_POST['id'] ?? 0) : (int) ($registro['id'] ?? 0);
    if ($acaoPost === 'selecionar') {
      $formRegistro = [];
      $formId = 0;
    }
    $formAcao = $formId ? 'atualizar' : 'criar';
    $historicoSelecionado = [];
    if ($formEntidade === 'jogador') {
      if ($erro !== '' && is_array($_POST['carreira'] ?? null)) {
        $historicoSelecionado = array_map('intval', $_POST['carreira']);
      } elseif ($formId) {
        $stmtHistorico = $pdo->prepare('SELECT time_id FROM jogador_carreira WHERE jogador_id=? ORDER BY ano_inicio, id');
        $stmtHistorico->execute([$formId]);
        $historicoSelecionado = array_map('intval', $stmtHistorico->fetchAll(PDO::FETCH_COLUMN));
      }
    }
    ?>
    <div class="admin-grid">
      <form class="form-admin" method="post">
        <h2><?= $formId ? 'Editar registro' : 'Novo registro' ?></h2>
        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="acao" value="<?= e($formAcao) ?>">
        <label>Tipo de registro
          <select name="entidade" required onchange="this.form.elements.acao.value='selecionar';this.form.submit()">
            <option value="">Selecione</option>
            <option value="noticia" <?= $formEntidade === 'noticia' ? 'selected' : '' ?>>Notícia</option>
            <option value="time" <?= $formEntidade === 'time' ? 'selected' : '' ?>>Time</option>
            <option value="jogador" <?= $formEntidade === 'jogador' ? 'selected' : '' ?>>Jogador</option>
          </select>
        </label>
        <?php if ($formId): ?><input type="hidden" name="id" value="<?= $formId ?>"><?php endif; ?>

        <?php if ($formEntidade === 'noticia'): ?>
          <label>Categoria<input name="categoria" maxlength="60" required value="<?= e($formRegistro['categoria'] ?? '') ?>"></label>
          <label>Título<input name="titulo" maxlength="180" required value="<?= e($formRegistro['titulo'] ?? '') ?>"></label>
          <label>Resumo<textarea name="resumo" maxlength="500" required><?= e($formRegistro['resumo'] ?? '') ?></textarea></label>
          <label>Conteúdo<textarea name="conteudo" required><?= e($formRegistro['conteudo'] ?? '') ?></textarea></label>
          <label>URL da imagem<input type="url" name="imagem" value="<?= e($formRegistro['imagem'] ?? '') ?>"></label>
        <?php elseif ($formEntidade === 'time'): ?>
          <label>Nome<input name="nome" maxlength="120" required value="<?= e($formRegistro['nome'] ?? '') ?>"></label>
          <label>Cidade<input name="cidade" maxlength="100" value="<?= e($formRegistro['cidade'] ?? '') ?>"></label>
          <label>UF/país (sigla)<input name="estado" maxlength="2" value="<?= e($formRegistro['estado'] ?? '') ?>"></label>
          <label>Ano de fundação<input type="number" name="fundacao" min="1" max="2100" value="<?= e($formRegistro['fundacao'] ?? '') ?>"></label>
        <?php elseif ($formEntidade === 'jogador'): ?>
          <label>Nome<input name="nome" maxlength="120" required value="<?= e($formRegistro['nome'] ?? '') ?>"></label>
          <label>Número da camisa<input type="number" name="numero" min="0" max="99" value="<?= e($formRegistro['numero'] ?? '') ?>"></label>
          <label>Nacionalidade<input name="nacionalidade" maxlength="80" value="<?= e($formRegistro['nacionalidade'] ?? '') ?>"></label>
          <label>Posição<input name="posicao" maxlength="80" value="<?= e($formRegistro['posicao'] ?? '') ?>"></label>
          <label>Valor de mercado estimado (R$)<input type="number" name="valor_mercado" min="0" max="9999999999999.99" step="0.01" value="<?= e($formRegistro['valor_mercado'] ?? '') ?>"></label>
          <label>Time<select name="time_id"><option value="">Sem time</option>
            <?php foreach ($times as $time): ?>
              <option value="<?= (int) $time['id'] ?>" <?= (string) ($formRegistro['time_id'] ?? '') === (string) $time['id'] ? 'selected' : '' ?>><?= e($time['nome']) ?></option>
            <?php endforeach; ?>
          </select></label>
          <label>Histórico de clubes (Ctrl/Command para selecionar vários)
            <select name="carreira[]" multiple size="8">
              <?php foreach ($times as $time): ?>
                <option value="<?= (int) $time['id'] ?>" <?= in_array((int) $time['id'], $historicoSelecionado, true) ? 'selected' : '' ?>><?= e($time['nome']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        <?php else: ?>
          <p>Selecione um tipo para preencher os campos de cadastro.</p>
        <?php endif; ?>
        <?php if ($formEntidade !== ''): ?><button class="botao" type="submit"><?= $formId ? 'Salvar alterações' : 'Cadastrar' ?></button><?php endif; ?>
      </form>

      <div class="admin-listas">
        <section class="lista-admin">
          <h2>Notícias <span><?= count($noticias) ?></span></h2>
          <?php foreach ($noticias as $item): ?>
            <div class="linha-admin"><span><?= e($item['titulo']) ?></span><div>
              <a href="/admin.php?entidade=noticia&amp;id=<?= (int) $item['id'] ?>">Editar</a>
              <form method="post" onsubmit="return confirm('Excluir esta notícia?')">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="entidade" value="noticia"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><button class="link-excluir" type="submit">Excluir</button>
              </form>
            </div></div>
          <?php endforeach; ?>
        </section>
        <section class="lista-admin">
          <h2>Times <span><?= count($times) ?></span></h2>
          <?php foreach ($times as $item): ?>
            <div class="linha-admin"><span><?= e($item['nome']) ?></span><div>
              <a href="/admin.php?entidade=time&amp;id=<?= (int) $item['id'] ?>">Editar</a>
              <form method="post" onsubmit="return confirm('Excluir este time? Jogadores vinculados ficarão sem time.')">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="entidade" value="time"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><button class="link-excluir" type="submit">Excluir</button>
              </form>
            </div></div>
          <?php endforeach; ?>
        </section>
        <section class="lista-admin">
          <h2>Jogadores <span><?= count($jogadores) ?></span></h2>
          <?php foreach ($jogadores as $item): ?>
            <div class="linha-admin"><span><?= e($item['nome']) ?></span><div>
              <a href="/admin.php?entidade=jogador&amp;id=<?= (int) $item['id'] ?>">Editar</a>
              <form method="post" onsubmit="return confirm('Excluir este jogador?')">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="entidade" value="jogador"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><button class="link-excluir" type="submit">Excluir</button>
              </form>
            </div></div>
          <?php endforeach; ?>
        </section>
      </div>
    </div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
