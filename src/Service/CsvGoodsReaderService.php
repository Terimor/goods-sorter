<?php

namespace App\Service;

use App\Entity\Good;
use App\Service\Constant\CsvConst;

class CsvGoodsReaderService
{
    public function read(string $filePath): array
    {
        $result = [];

        $handle = fopen($filePath, 'rw+');

        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, CsvConst::DELIMITER)) !== false) {
            $marketplace = $data[CsvConst::MARKETPLACE];
            $sku = $data[CsvConst::SELLER_SKU];
            $description = $data[CsvConst::ITEM_DESCRIPTION];
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
}