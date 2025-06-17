<?php

namespace App\DataTransferObjects;

class CrimeOutcomeDTO
{
    public function __construct(
        public readonly bool $wasSuccessful,
        public readonly string $message,
        public readonly ?int $moneyGained = null,
        public readonly ?int $experienceGained = null
    ) {
    }
}
