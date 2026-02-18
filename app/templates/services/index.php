<section class="section-header">
  <div>
    <h2>Services</h2>
    <p class="muted">Manage subdomains mapped to local ports.</p>
  </div>
  <a class="btn" href="/services/new">New service</a>
</section>

<?php if (!empty($message)): ?>
  <div class="flash success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="table">
  <div class="table-row table-head">
    <div>Name</div>
    <div>Full Domain</div>
    <div>Local Port</div>
    <div>Protocol</div>
    <div>Status</div>
    <div></div>
  </div>
  <?php foreach ($services as $service): ?>
    <div class="table-row">
      <div><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></div>
      <div style="font-family: monospace; font-size: 13px;">
          <?php if (!empty($service['is_custom_domain'])): ?>
              <?= htmlspecialchars($service['domain'], ENT_QUOTES, 'UTF-8') ?>
          <?php else: ?>
              <?= htmlspecialchars($service['subdomain'] . '.' . ($service['domain'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
          <?php endif; ?>
      </div>
      <div><?= (int)$service['local_port'] ?></div>
      <div><?= htmlspecialchars($service['protocol'], ENT_QUOTES, 'UTF-8') ?></div>
      <div>
        <span class="status-pill <?= $service['enabled'] ? 'enabled' : 'disabled' ?>">
          <?= $service['enabled'] ? 'Enabled' : 'Disabled' ?>
        </span>
      </div>
      <div>
        <div style="display: flex; gap: 8px;">
          <a href="/services/setup?id=<?= (int)$service['id'] ?>" class="btn ghost">Setup</a>
          <form method="post" action="/services/toggle">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Auth::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="id" value="<?= (int)$service['id'] ?>">
            <button class="btn ghost" type="submit">Toggle</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if (empty($services)): ?>
  <p class="muted">No services yet. Create your first one.</p>
<?php endif; ?>
