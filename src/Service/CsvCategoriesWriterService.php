<?php

namespace App\Service;

use App\Entity\CategoryGoods;
use App\Service\Constant\CsvConst;
use Symfony\Component\HttpKernel\KernelInterface;

class CsvCategoriesWriterService
{
    private const CATEGORY_PATH_PATTERN = '%s/var/categories/%s.csv';
    private const CATEGORY_FOLDER_PATH = '%s/var/categories/*';

    private const DEFAULT_TOTAL = '!';

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
        $filePath = sprintf(self::CATEGORY_PATH_PATTERN, $this->kernel->getProjectDir(), $categoryGoods->getCategoryName());
        $fp = fopen($filePath, 'w');

        fputcsv($fp, CsvConst::HEADERS, CsvConst::DELIMITER);
        foreach ($categoryGoods->getGoods() as $good) {
            fputcsv(
                $fp,
                [
                    CsvConst::MARKETPLACE => $good->getMarketplace(),
                    CsvConst::SELLER_SKU => $good->getSku(),
                    CsvConst::ITEM_DESCRIPTION => $good->getDescription(),
                    CsvConst::QTY => $good->getAmount(),
                    CsvConst::TOTAL => $good->getTotal() ?: self::DEFAULT_TOTAL,
                ],
                CsvConst::DELIMITER
            );
        }
    }
}