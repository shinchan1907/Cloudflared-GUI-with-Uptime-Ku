<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth;
use App\Config;
use App\Service\ServiceRepository;

final class AgentController
{
    private ServiceRepository $repo;

    public function __construct()
    {
        $this->repo = new ServiceRepository();
    }

    public function install(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $service = $this->repo->find($id);

        if (!$service) {
            http_response_code(404);
            echo "Service not found";
            return;
        }

        $appName = Config::get('APP_NAME', 'Cloudflared Panel');
        $protocol = $service['protocol'];
        $port = (int)$service['local_port'];
        $fullDomain = !empty($service['is_custom_domain']) 
            ? $service['domain'] 
            : $service['subdomain'] . '.' . ($service['domain'] ?: Config::get('PRIMARY_DOMAIN', 'example.com'));
        $panelUrl = Config::get('APP_URL', 'http://localhost:5555');

        header('Content-Type: text/x-shellscript');
        
        echo "#!/bin/bash\n";
        echo "set -e\n\n";
        
        echo "echo '🚀 Starting Enterprise One-Line Installer for $appName'\n";
        echo "echo '📦 Service: " . $service['name'] . " ($fullDomain)'\n\n";

        echo "# 1. Architecture Detection\n";
        echo "ARCH=$(uname -m)\n";
        echo "case \$ARCH in\n";
        echo "  x86_64) BIN_ARCH='amd64' ;;\n";
        echo "  aarch64|arm64) BIN_ARCH='arm64' ;;\n";
        echo "  *) echo \"❌ Unsupported architecture: \$ARCH\"; exit 1 ;;\n";
        echo "esac\n\n";

        echo "# 2. Download Cloudflared\n";
        echo "echo '⬇️ Downloading cloudflared...'\n";
        echo "if ! command -v cloudflared &> /dev/null; then\n";
        echo "  curl -L https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-\$BIN_ARCH -o /tmp/cloudflared\n";
        echo "  sudo chmod +x /tmp/cloudflared\n";
        echo "  sudo mv /tmp/cloudflared /usr/local/bin/cloudflared\n";
        echo "fi\n\n";

        echo "# 3. Generate Quick Tunnel for Testing\n";
        echo "echo '⚡ Starting Tunnel...'\n";
        echo "echo '----------------------------------------------------------------'\n";
        echo "echo 'Your service $fullDomain is now being connected.'\n";
        echo "echo 'Press Ctrl+C to stop the temporary tunnel.'\n";
        echo "echo '----------------------------------------------------------------'\n";
        
        echo "cloudflared tunnel --url $protocol://localhost:$port\n";
    }
}
