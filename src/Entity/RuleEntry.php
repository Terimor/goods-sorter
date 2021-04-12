<?php


namespace App\Entity;


class RuleEntry
{
    private const NOT_CHARACTER = '!';
    private const ENTRIES_SEPARATOR = '-';

    /** @var string[] $entries */
    private $entries = [];

    /** @var string[] $stopEntries */
    private $stopEntries = [];

    public function __construct(string $totalEntries)
    {
        foreach(explode(self::ENTRIES_SEPARATOR, $totalEntries) as $entry) {
            $trimmedEntry = trim($entry);
            if (str_starts_with($trimmedEntry, self::NOT_CHARACTER)) {
                $this->stopEntries[] = trim($trimmedEntry, self::NOT_CHARACTER);
            } else {
                $this->entries[] = $trimmedEntry;
            }
        }
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function getStopEntries(): array
    {
        return $this->stopEntries;
    }
}