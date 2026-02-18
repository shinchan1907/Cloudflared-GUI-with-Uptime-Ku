/** @var array $service */
/** @var string $primaryDomain */
$fullDomain = !empty($service['is_custom_domain']) 
    ? $service['domain'] 
    : $service['subdomain'] . '.' . ($service['domain'] ?: $primaryDomain);
$serviceId = 'svc-' . $service['id'];
?>
<div class="section-header">
  <div>
    <h1>Finish Setup: <?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p class="muted">Run the command below on your local machine to connect instantly.</p>
  </div>
  <a href="/services" class="btn ghost">Back to Services</a>
</div>

<section class="grid">
  <div class="card" style="grid-column: span 2; border: 2px solid var(--accent); background: #f0f7ff;">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
       <span style="background: var(--accent); color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;">RECOMMENDED</span>
       <h3 style="margin: 0;">Enterprise One-Line Setup</h3>
    </div>
    <p class="hint">Downloads, installs, and connects your service in one step (Linux/macOS).</p>
    <div class="code-block" style="background: #0f172a; color: #f8fafc; padding: 20px; border-radius: 12px; font-family: monospace; margin-top: 16px; position: relative; border: 1px solid #334155;">
      <pre style="margin: 0; white-space: pre-wrap; color: #38bdf8;">curl -sSL "<?= htmlspecialchars(App\Config::get('APP_URL', 'http://localhost:5555'), ENT_QUOTES, 'UTF-8') ?>/agent/install?id=<?= (int)$service['id'] ?>" | bash</pre>
    </div>
  </div>

  <div class="card" style="grid-column: span 2;">
    <h3>1. Install Cloudflared</h3>
    <p class="hint">Ensure you have the Cloudflare Tunnel client installed on your local machine.</p>
    <div class="code-block" style="background: #1e293b; color: #f8fafc; padding: 16px; border-radius: 8px; font-family: monospace; margin-top: 12px; position: relative;">
      <pre style="margin: 0; white-space: pre-wrap;"># Windows (Powershell)
winget install Cloudflare.cloudflared

# Linux (Debian/Ubuntu)
curl -L https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb -o cloudflared.deb
sudo dpkg -i cloudflared.deb</pre>
    </div>
  </div>

  <div class="card" style="grid-column: span 2;">
    <h3>2. Run Your Tunnel</h3>
    <p class="hint">Use this "Quick Tunnel" command to instantly expose your port. For production, we recommend using a Named Tunnel.</p>
    <div class="code-block" style="background: #1e293b; color: #f8fafc; padding: 16px; border-radius: 8px; font-family: monospace; margin-top: 12px;">
      <pre style="margin: 0; white-space: pre-wrap;">cloudflared tunnel --url <?= htmlspecialchars($service['protocol'], ENT_QUOTES, 'UTF-8') ?>://localhost:<?= (int)$service['local_port'] ?></pre>
    </div>
    <p class="hint" style="margin-top: 12px;">Cloudflare will provide a temporary <code>.trycloudflare.com</code> address.</p>
  </div>

  <div class="card" style="grid-column: span 2;">
    <h3>3. Production Integration (Named Tunnel)</h3>
    <p class="hint">To use <strong><?= htmlspecialchars($fullDomain, ENT_QUOTES, 'UTF-8') ?></strong>, follow these steps:</p>
    <div class="code-block" style="background: #1e293b; color: #f8fafc; padding: 16px; border-radius: 8px; font-family: monospace; margin-top: 12px;">
      <pre style="margin: 0; white-space: pre-wrap;"># A. Login to Cloudflare
cloudflared tunnel login

# B. Create the Tunnel
cloudflared tunnel create <?= htmlspecialchars($serviceId, ENT_QUOTES, 'UTF-8') ?>

# C. Route to DNS
cloudflared tunnel route dns <?= htmlspecialchars($serviceId, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($fullDomain, ENT_QUOTES, 'UTF-8') ?>

# D. Start the Tunnel
cloudflared tunnel run --url <?= htmlspecialchars($service['protocol'], ENT_QUOTES, 'UTF-8') ?>://localhost:<?= (int)$service['local_port'] ?> <?= htmlspecialchars($serviceId, ENT_QUOTES, 'UTF-8') ?></pre>
    </div>
  </div>
</section>

<style>
.code-block pre {
  user-select: all;
  cursor: pointer;
}
.code-block pre::before {
  content: 'CLICK TO COPY';
  position: absolute;
  top: 8px;
  right: 8px;
  font-size: 10px;
  background: var(--accent);
  padding: 2px 6px;
  border-radius: 4px;
  opacity: 0.5;
}
</style>
