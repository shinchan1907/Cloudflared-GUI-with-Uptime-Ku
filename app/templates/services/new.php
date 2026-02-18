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

    <div style="margin-top: 10px; padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid var(--border);">
        <label style="margin-bottom: 12px;">Domain Type</label>
        <div style="display: flex; gap: 20px;">
            <label style="font-weight: 400; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="domain_type" value="subdomain" checked onclick="toggleDomainFields('subdomain')"> 
                Subdomain Mode
            </label>
            <label style="font-weight: 400; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="radio" name="domain_type" value="custom" onclick="toggleDomainFields('custom')"> 
                Full Custom Domain
            </label>
        </div>
    </div>

    <!-- Subdomain Fields -->
    <div id="subdomain_fields">
        <label>
          Subdomain
          <input type="text" name="subdomain" placeholder="myapp">
          <span class="hint">The subdomain on <strong><?= htmlspecialchars($primaryDomain, ENT_QUOTES, 'UTF-8') ?></strong></span>
        </label>
    </div>

    <!-- Custom Domain Fields -->
    <div id="custom_fields" style="display: none;">
        <label>
          Custom Domain
          <input type="text" name="custom_domain" placeholder="app.mycustomdomain.com or my-site.com">
          <span class="hint">Enter the full domain you want to use.</span>
        </label>
    </div>

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

<script>
function toggleDomainFields(type) {
    const sub = document.getElementById('subdomain_fields');
    const custom = document.getElementById('custom_fields');
    if (type === 'subdomain') {
        sub.style.display = 'block';
        custom.style.display = 'none';
    } else {
        sub.style.display = 'none';
        custom.style.display = 'block';
    }
}
</script>
