<div class="section-header">
  <h1>Add New Service</h1>
  <a href="/services" class="btn ghost">Back to List</a>
</div>

<section class="card narrow">
  <?php if (!empty($error)): ?>
    <div class="flash error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="/services" class="form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Auth::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    
    <label>
      Service Name
      <input type="text" name="name" placeholder="My Awesome App" required>
      <span class="hint">Friendly name for your service</span>
    </label>

    <label>
      Subdomain
      <input type="text" name="subdomain" placeholder="myapp" required>
      <span class="hint">The subdomain on your primary domain (e.g., myapp.example.com)</span>
    </label>

    <label>
      Local Port
      <input type="number" name="local_port" placeholder="3000" required>
      <span class="hint">The port your local app is running on</span>
    </label>

    <label>
      Protocol
      <select name="protocol">
        <option value="http" selected>HTTP</option>
        <option value="https">HTTPS</option>
      </select>
      <span class="hint">Protocol your local service uses</span>
    </label>

    <div class="actions">
      <button class="btn" type="submit">Create Service</button>
      <a href="/services" class="btn ghost">Cancel</a>
    </div>
  </form>
</section>
