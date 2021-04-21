<?php

namespace App\Entity;

class Good
{
    private const DESCRIPTION_VOLUME_REGEX = '#(\d+(?:[\.,]{0,1})\d*)(Kg|kg|L|KG)#';

    /** @var string $marketplace */
    private $marketplace;

    /** @var string $sku */
    private $sku;

    /** @var int $amount */
    private $amount;

    /** @var string $description */
    private $description;

    public function __construct(string $marketplace, string $sku, int $amount, string $description)
    {
        $this->marketplace = $marketplace;
        $this->sku = $sku;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function getMarketplace(): string
    {
        return $this->marketplace;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTotal(): ?float
    {
        $volume = $this->getVolume();

        if ($volume) {
            return $volume->getAmount() * $this->amount;
        }

        return null;
    }

    public function getVolume(): ?Volume
    {
        preg_match(self::DESCRIPTION_VOLUME_REGEX, $this->getDescription(), $matches);

        if (isset($matches[1]) && isset($matches[2])) {
            $amount = (float)str_replace(',', '.', $matches[1]);
            $units = $matches[2];

            return new Volume($amount, $units);
        } else {
            return null;
        }
    }

    public function isRuleCorrespond(Rule $rule): bool
    {
        $informativeString = $this->getSku().$this->getDescription();

        $checkEntry = function (RuleEntry $ruleEntry) use ($informativeString) {
            foreach ($ruleEntry->getEntries() as $entry) {
                if (mb_stripos($informativeString, $entry) === false) {
                    return false;
                }
            }

            foreach ($ruleEntry->getStopEntries() as $stopEntry) {
                if (mb_stripos($informativeString, $stopEntry) !== false) {
                    return false;
                }
            }

            return true;
        };

        foreach ($rule->getEntries() as $ruleEntry) {
            if ($checkEntry($ruleEntry)) {
                return true;
            }
        }

        return false;
    }
}