<?php


namespace App\Service;


use App\Entity\CategoryGoods;
use App\Service\Constant\CsvConst;
use Symfony\Component\HttpKernel\KernelInterface;

class CsvCategoriesReportWriterService
{
    /** @var KernelInterface $kernel */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function write(array $categoriesGoods): void
    {
        $fp = fopen(sprintf(CsvConst::CATEGORY_PATH_PATTERN, $this->kernel->getProjectDir(), 'total'), 'wb');

        /** @var CategoryGoods $categoryGood */
        foreach ($categoriesGoods as $categoryGood) {
            $this->writeCsvLine($fp, $categoryGood->getCategoryName(), $categoryGood->getTotalAmount(), $categoryGood->getAmountWithoutVolume());
        }

        fclose($fp);
    }

    private function writeCsvLine($fp, ?string $categoryName, ?float $totalAmount, ?int $goodsAmountWithoutVolume): void
    {
        fputcsv(
            $fp,
            [
                CsvConst::CATEGORY_NAME => $categoryName,
                CsvConst::TOTAL_AMOUNT => $totalAmount,
                CsvConst::GOODS_AMOUNT_WITHOUT_VOLUME => $goodsAmountWithoutVolume
            ],
            CsvConst::DELIMITER
        );
    }
}