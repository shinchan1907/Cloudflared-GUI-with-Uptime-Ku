<section class="card narrow">
  <h2>Login</h2>
  <?php if (!empty($error)): ?>
    <div class="flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <form method="post" action="/login" class="form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Auth::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    <label>
      Email
      <input type="email" name="email" required>
    </label>
    <label>
      Password
      <input type="password" name="password" required>
    </label>
    <button class="btn" type="submit">Sign in</button>
  </form>
</section>
