<?php

declare(strict_types=1);

namespace Sendly;

use DateTimeImmutable;

class ApiKey
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $prefix;
    public readonly ?string $lastUsedAt;
    public readonly DateTimeImmutable $createdAt;
    public readonly ?DateTimeImmutable $expiresAt;
    public readonly bool $isActive;

    /**
     * @param array<string, mixed> $data Response data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->prefix = $data['prefix'] ?? '';
        $this->lastUsedAt = $data['last_used_at'] ?? $data['lastUsedAt'] ?? null;
        $this->createdAt = $this->parseDateTime($data['created_at'] ?? $data['createdAt'] ?? null) ?? new DateTimeImmutable();
        $this->expiresAt = $this->parseDateTime($data['expires_at'] ?? $data['expiresAt'] ?? null);
        $this->isActive = (bool) ($data['is_active'] ?? $data['isActive'] ?? true);
    }

    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }
        return $this->expiresAt < new DateTimeImmutable();
    }

    private function parseDateTime(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        try {
            return new DateTimeImmutable($value);
        } catch (\Exception) {
            return null;
        }
    }
}
