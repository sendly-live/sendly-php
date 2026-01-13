<?php

declare(strict_types=1);

namespace Sendly;

use DateTimeImmutable;

class AccountVerification
{
    public readonly bool $emailVerified;
    public readonly bool $phoneVerified;
    public readonly bool $identityVerified;

    /**
     * @param array<string, mixed> $data Response data
     */
    public function __construct(array $data)
    {
        $this->emailVerified = (bool) ($data['email_verified'] ?? $data['emailVerified'] ?? false);
        $this->phoneVerified = (bool) ($data['phone_verified'] ?? $data['phoneVerified'] ?? false);
        $this->identityVerified = (bool) ($data['identity_verified'] ?? $data['identityVerified'] ?? false);
    }

    /**
     * Check if fully verified
     */
    public function isFullyVerified(): bool
    {
        return $this->emailVerified && $this->phoneVerified && $this->identityVerified;
    }
}

/**
 * Represents account limits
 */
class AccountLimits
{
    public readonly int $messagesPerSecond;
    public readonly int $messagesPerDay;
    public readonly int $maxBatchSize;

    /**
     * @param array<string, mixed> $data Response data
     */
    public function __construct(array $data)
    {
        $this->messagesPerSecond = (int) ($data['messages_per_second'] ?? $data['messagesPerSecond'] ?? 10);
        $this->messagesPerDay = (int) ($data['messages_per_day'] ?? $data['messagesPerDay'] ?? 10000);
        $this->maxBatchSize = (int) ($data['max_batch_size'] ?? $data['maxBatchSize'] ?? 1000);
    }
}

/**
 * Represents account information
 */
class Account
{
    public readonly string $id;
    public readonly string $email;
    public readonly ?string $name;
    public readonly ?string $companyName;
    public readonly AccountVerification $verification;
    public readonly AccountLimits $limits;
    public readonly DateTimeImmutable $createdAt;

    /**
     * @param array<string, mixed> $data Response data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->name = $data['name'] ?? null;
        $this->companyName = $data['company_name'] ?? $data['companyName'] ?? null;
        $this->verification = new AccountVerification($data['verification'] ?? []);
        $this->limits = new AccountLimits($data['limits'] ?? []);
        $this->createdAt = $this->parseDateTime($data['created_at'] ?? $data['createdAt'] ?? null) ?? new DateTimeImmutable();
    }

    /**
     * Parse a datetime string
     */
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
