<section class="card narrow">
  <h2>New Service</h2>
  <?php if (!empty($error)): ?>
    <div class="flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <form method="post" action="/services" class="form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Auth::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    <label>
      Service name
      <input type="text" name="name" required>
    </label>
    <label>
      Subdomain
      <input type="text" name="subdomain" placeholder="app1" required>
      <span class="hint">Full domain will be subdomain + primary domain.</span>
    </label>
    <label>
      Local port
      <input type="number" name="local_port" min="1" max="65535" required>
    </label>
    <label>
      Protocol
      <select name="protocol">
        <option value="http">http</option>
        <option value="https">https</option>
      </select>
    </label>
    <button class="btn" type="submit">Create service</button>
  </form>
</section>
