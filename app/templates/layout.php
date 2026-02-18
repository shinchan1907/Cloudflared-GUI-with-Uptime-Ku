<?php
/** @var string $templatePath */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($appName ?? 'Control Panel', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-row">
      <div>
        <div class="brand"><?= htmlspecialchars($appName ?? 'Control Panel', ENT_QUOTES, 'UTF-8') ?></div>
        <div class="tagline">Cloudflare Tunnel + Uptime Kuma control plane</div>
      </div>
      <nav class="header-nav">
        <?php if (App\Auth::check()): ?>
          <a href="/" class="nav-link">Dashboard</a>
          <a href="/services" class="nav-link">Services</a>
          <a href="/settings" class="nav-link">Settings</a>
        <?php endif; ?>
      </nav>
      <div class="header-actions">
        <?php if (App\Auth::check()): ?>
          <form action="/logout" method="post">
            <button class="btn ghost" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a class="btn ghost" href="/login">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <main class="container">
    <?php include $templatePath; ?>
  </main>

  <footer class="site-footer">
    <div class="container">
      <span>Prototype scaffold. Next: Cloudflare API + agent onboarding.</span>
    </div>
  </footer>
</body>
</html>
