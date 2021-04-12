<?php

namespace App\Service\Constant;

class CsvConst
{
    public const HEADERS = [
        self::MARKETPLACE => 'MARKETPLACE',
        self::SELLER_SKU => 'SELLER_SKU',
        self::ITEM_DESCRIPTION => 'ITEM_DESCRIPTION',
        self::QTY => 'QTY',
        self::TOTAL => 'TOTAL',
    ];

    public const MARKETPLACE = 0;
    public const SELLER_SKU = 1;
    public const ITEM_DESCRIPTION = 2;
    public const QTY = 3;
    public const TOTAL = 4;

    public const DELIMITER = ';';
}