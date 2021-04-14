<?php

namespace App\Service\Constant;

class CsvConst
{
    public const GOODS_HEADERS = [
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

    public const CATEGORY_NAME = 0;
    public const TOTAL_AMOUNT = 1;
    public const GOODS_AMOUNT_WITHOUT_VOLUME = 2;

    public const DELIMITER = ';';
    public const CATEGORY_PATH_PATTERN = '%s/var/categories/%s.csv';
}