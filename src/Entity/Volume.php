<?php


namespace App\Entity;


class Volume
{
    /** @var float $amount */
    private $amount;

    /** @var string $units */
    private $units;

    /**
     * @param float $amount
     * @param string $units
     */
    public function __construct(float $amount, string $units)
    {
        $this->amount = $amount;
        $this->units = strtolower($units);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getUnits(): string
    {
        return $this->units;
    }

    public function getAmountWithUnits(): string
    {
        return $this->amount.$this->units;
    }

    public function isLiters(): bool
    {
        return $this->units === 'l';
    }

    public function isKilograms(): bool
    {
        return $this->units === 'kg';
    }
}