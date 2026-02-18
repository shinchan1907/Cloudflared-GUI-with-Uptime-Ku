<?php

declare(strict_types=1);

namespace App\Service;

use App\Config;

final class CloudflareService
{
    private string $apiToken;
    private string $zoneId;
    private string $accountId;

    public function __construct()
    {
        $this->apiToken = Config::get('CF_API_TOKEN', '');
        $this->zoneId = Config::get('CF_ZONE_ID', '');
        $this->accountId = Config::get('CF_ACCOUNT_ID', '');
    }

    public function createDnsRecord(string $subdomain, string $target): bool
    {
        if (!$this->apiToken || !$this->zoneId) {
            return false;
        }

        $url = "https://api.cloudflare.com/client/v4/zones/{$this->zoneId}/dns_records";
        
        $data = [
            'type' => 'CNAME',
            'name' => $subdomain,
            'content' => $target,
            'ttl' => 120,
            'proxied' => true
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->apiToken}",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status === 200 || $status === 201;
    }

    /**
     * In a production scenario, we would also automate Tunnel creation.
     * For this prototype, we're focusing on the DNS mapping.
     */
}
