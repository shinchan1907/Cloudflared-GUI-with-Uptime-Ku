<div class="section-header">
  <h1>System Settings</h1>
  <p class="muted">Configure your Cloudflare integration and platform defaults.</p>
</div>

<?php if (!empty($message)): ?>
  <div class="flash success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<section class="grid">
  <div class="card" style="grid-column: span 2;">
    <h3>Cloudflare Integration</h3>
    <p class="hint">These credentials allow the platform to manage DNS records and Tunnels.</p>
    
    <form method="post" action="/settings" class="form" style="margin-top: 20px;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(App\Auth::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
      
      <label>
        Cloudflare API Token
        <input type="password" name="cf_token" value="<?= htmlspecialchars($cfToken, ENT_QUOTES, 'UTF-8') ?>" placeholder="••••••••••••••••">
        <span class="hint">Requires Zone:DNS:Edit and Account:Tunnel:Edit permissions.</span>
      </label>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <label>
          Account ID
          <input type="text" name="cf_account" value="<?= htmlspecialchars($cfAccount, ENT_QUOTES, 'UTF-8') ?>" placeholder="32-char hex string">
        </label>
        <label>
          Zone ID
          <input type="text" name="cf_zone" value="<?= htmlspecialchars($cfZone, ENT_QUOTES, 'UTF-8') ?>" placeholder="32-char hex string">
        </label>
      </div>

      <label>
        Primary Domain
        <input type="text" name="primary_domain" value="<?= htmlspecialchars($primaryDomain, ENT_QUOTES, 'UTF-8') ?>" placeholder="example.com">
        <span class="hint">The apex domain used for all published services.</span>
      </label>

      <div class="actions">
        <button class="btn" type="submit">Save Changes</button>
      </div>
    </form>
  </div>

  <div class="card">
    <h3>Uptime Kuma</h3>
    <p class="hint">Connection status for health monitoring.</p>
    <div style="margin-top: 16px;">
      <span class="status-pill enabled">Connected</span>
      <p style="font-size: 13px; margin-top: 12px;">Monitoring is active and will auto-provision for new services.</p>
    </div>
  </div>
</section>
