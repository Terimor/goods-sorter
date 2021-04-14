<?php

namespace App\Service;

use App\Entity\CategoryGoods;
use App\Service\Constant\CsvConst;
use Symfony\Component\HttpKernel\KernelInterface;

class CsvCategoriesWriterService
{
    private const CATEGORY_FOLDER_PATH = '%s/var/categories/*';

    private const DEFAULT_TOTAL = 0;

    /** @var KernelInterface $kernel */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function write(array $categoryGoodsList): void
    {
        $this->clearCategoriesFolder();

        /** @var CategoryGoods $categoryGoods */
        foreach ($categoryGoodsList as $categoryGoods) {
            $this->writeCategoryFile($categoryGoods);
        }
    }

    private function clearCategoriesFolder(): void
    {
        $files = glob(sprintf(self::CATEGORY_FOLDER_PATH, $this->kernel->getProjectDir()));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function writeCategoryFile(CategoryGoods $categoryGoods): void
    {
        $filePath = sprintf(CsvConst::CATEGORY_PATH_PATTERN, $this->kernel->getProjectDir(), $categoryGoods->getCategoryName());
        $fp = fopen($filePath, 'w');

        fputcsv($fp, CsvConst::GOODS_HEADERS, CsvConst::DELIMITER);
        foreach ($categoryGoods->getGoods() as $good) {
            $this->writeCsvLine($fp, $good->getMarketplace(), $good->getSku(), $good->getDescription(), $good->getAmount(), $good->getTotal() ?: self::DEFAULT_TOTAL);
        }

        $this->writeCsvLine($fp, null, null, null, null, $categoryGoods->getTotalAmount());

        fclose($fp);
    }

    private function writeCsvLine($fp, ?string $marketplace, ?string $sellerSku, ?string $itemDescription, ?string $qty, ?string $total): void
    {
        fputcsv(
            $fp,
            [
                CsvConst::MARKETPLACE => $marketplace,
                CsvConst::SELLER_SKU => $sellerSku,
                CsvConst::ITEM_DESCRIPTION => $itemDescription,
                CsvConst::QTY => $qty,
                CsvConst::TOTAL => $total,
            ],
            CsvConst::DELIMITER
        );
    }
}