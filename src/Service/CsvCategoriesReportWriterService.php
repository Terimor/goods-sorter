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
        $fp = fopen(sprintf(CsvConst::CATEGORY_PATH_PATTERN, $this->kernel->getProjectDir(), 'total'), 'w');

        /** @var CategoryGoods $categoryGood */
        foreach ($categoriesGoods as $categoryGood) {
            $this->writeCsvLine($fp, $categoryGood->getCategoryName(), $categoryGood->getTotalAmount(), $categoryGood->getAmountWithoutVolume());

            foreach ($categoryGood->getContainersAmount() as $container => $amount) {
                $this->writeCsvLine($fp, $container, $amount, (int) filter_var($container, FILTER_SANITIZE_NUMBER_INT) * $amount);
            }
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