</main>
<footer>
  <a class="logo" href="/"><span>FUT</span>NEX</a>
  <p>Seu portal simples e direto sobre futebol.</p>
  <form class="preferencia" action="/preferencia.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <button type="submit" name="tema" value="<?= ($_COOKIE['futnex_tema'] ?? '') === 'escuro' ? 'claro' : 'escuro' ?>">
      <?= ($_COOKIE['futnex_tema'] ?? '') === 'escuro' ? 'Usar tema claro' : 'Usar tema escuro' ?>
    </button>
  </form>
  <small>&copy; <?= date('Y') ?> FUTNEX</small>
</footer>
</body>
</html>
