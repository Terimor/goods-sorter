<?php

namespace App\Service;

use App\Entity\Good;
use App\Entity\ReplacementRule;
use App\Service\Constant\CsvConst;

class CsvGoodsReaderService
{
    public function read(string $filePath, string $replacementRulesFilePath): array
    {
        $result = [];
        $replacementRules = $this->getReplacementRules($replacementRulesFilePath);

        $handle = fopen($filePath, 'rwb+');

        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, CsvConst::DELIMITER)) !== false) {
            $marketplace = $data[CsvConst::MARKETPLACE];
            $sku = $this->replaceStringByRules($data[CsvConst::SELLER_SKU], $replacementRules);
            $description = $this->replaceStringByRules($data[CsvConst::ITEM_DESCRIPTION], $replacementRules);
            $amount = (int)$data[CsvConst::QTY];

            $good = new Good(
                $marketplace,
                $sku,
                $amount,
                $description
            );

            $result[] = $good;
        }

        return $result;
    }

    private function getReplacementRules(string $replacementRulesFilePath): array
    {
        $result = [];

        $handle = fopen($replacementRulesFilePath, 'rwb+');

        while(($data = fgetcsv($handle, 1000, CsvConst::DELIMITER)) !== false) {
            $from = $data[CsvConst::REPLACEMENT_FROM];
            $to = $data[CsvConst::REPLACEMENT_TO];

            $replacementRule = new ReplacementRule($from, $to);

            $result[] = $replacementRule;
        }

        return $result;
    }

    private function replaceStringByRules(string $string, array $rules): string
    {
        $result = $string;

        /** @var ReplacementRule $rule */
        foreach ($rules as $rule) {
            $result = str_replace($rule->getFrom(), $rule->getTo(), $result);
        }

        return $result;
    }
}