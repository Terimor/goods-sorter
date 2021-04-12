<?php


namespace App\Entity;


class Rule
{
    /** @var string $categoryName */
    private $categoryName;

    /** @var RuleEntry[] $entries */
    private $entries = [];

    public function __construct(string $categoryName)
    {
        $this->categoryName = $categoryName;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function addEntry(RuleEntry $entry): void
    {
        $this->entries[] = $entry;
    }
}