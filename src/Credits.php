<?php

declare(strict_types=1);

namespace Sendly;

class Credits
{
    public readonly int $balance;
    public readonly int $availableBalance;
    public readonly int $pendingCredits;
    public readonly int $reservedCredits;
    public readonly string $currency;

    /**
     * @param array<string, mixed> $data Response data
     */
    public function __construct(array $data)
    {
        $this->balance = (int) ($data['balance'] ?? 0);
        $this->availableBalance = (int) ($data['available_balance'] ?? $data['availableBalance'] ?? $data['balance'] ?? 0);
        $this->pendingCredits = (int) ($data['pending_credits'] ?? $data['pendingCredits'] ?? 0);
        $this->reservedCredits = (int) ($data['reserved_credits'] ?? $data['reservedCredits'] ?? 0);
        $this->currency = $data['currency'] ?? 'USD';
    }

    public function hasCredits(): bool
    {
        return $this->availableBalance > 0;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'balance' => $this->balance,
            'available_balance' => $this->availableBalance,
            'pending_credits' => $this->pendingCredits,
            'reserved_credits' => $this->reservedCredits,
            'currency' => $this->currency,
        ];
    }
}
