<?php


namespace App\Service;


use App\Entity\Rule;
use App\Entity\RuleEntry;
use App\Service\Constant\CsvConst;

class CsvRulesReaderService
{
    public function read(string $filePath): array
    {
        $result = [];

        $handle = fopen($filePath, 'rw+');

        foreach ($this->getCsv($handle) as $key => $categoryName) {
            $result[$key] = new Rule($categoryName);
        }

        while (($row = $this->getCsv($handle)) !== false) {
            foreach ($row as $key => $entry) {
                if ($entry) {
                    $ruleEntry = new RuleEntry($entry);
                    $result[$key]->addEntry($ruleEntry);
                }
            }
        }

        return $result;
    }

    /**
     * @param $handle
     * @return string[]|bool
     */
    private function getCsv($handle)
    {
        return fgetcsv($handle, 1000, CsvConst::DELIMITER);
    }
}