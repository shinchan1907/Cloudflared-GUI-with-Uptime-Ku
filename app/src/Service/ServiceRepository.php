<?php

declare(strict_types=1);

namespace App\Service;

use App\Database;
use PDO;

final class ServiceRepository
{
    public function ensureTable(): void
    {
        Database::pdo()->exec("CREATE TABLE IF NOT EXISTS services (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(190) NOT NULL,
            subdomain VARCHAR(190) NULL,
            domain VARCHAR(190) NULL,
            is_custom_domain TINYINT(1) NOT NULL DEFAULT 0,
            local_port INT NOT NULL,
            protocol VARCHAR(10) NOT NULL DEFAULT 'http',
            enabled TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $this->ensureTable();
        $stmt = Database::pdo()->query('SELECT * FROM services ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $this->ensureTable();
        $stmt = Database::pdo()->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(string $name, ?string $subdomain, ?string $domain, int $isCustomDomain, int $localPort, string $protocol): int
    {
        $this->ensureTable();
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('INSERT INTO services (name, subdomain, domain, is_custom_domain, local_port, protocol) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $subdomain, $domain, $isCustomDomain, $localPort, $protocol]);
        return (int)$pdo->lastInsertId();
    }

    public function toggle(int $id): void
    {
        $this->ensureTable();
        $stmt = Database::pdo()->prepare('UPDATE services SET enabled = IF(enabled = 1, 0, 1) WHERE id = ?');
        $stmt->execute([$id]);
    }
}
