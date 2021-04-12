<?php

namespace App\Facade;

use App\Service\CsvCategoriesWriterService;
use App\Service\CsvGoodsReaderService;
use App\Service\CsvRulesReaderService;
use App\Service\SorterService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class SortFacade
{
    private const RULES_FILE_PATH_PATTERN = '%s/var/rules/rules.csv';

    /** @var KernelInterface $kernelInterface */
    private $kernelInterface;

    /** @var CsvGoodsReaderService $csvGoodsReaderService */
    private $csvGoodsReaderService;

    /** @var CsvRulesReaderService $csvRulesReaderService */
    private $csvRulesReaderService;

    /** @var SorterService $sorterService */
    private $sorterService;

    /** @var CsvCategoriesWriterService $csvCategoriesWriterService */
    private $csvCategoriesWriterService;

    public function __construct(
        KernelInterface $kernelInterface,
        CsvGoodsReaderService $csvGoodsReaderService,
        CsvRulesReaderService $csvRulesReaderService,
        SorterService $sorterService,
        CsvCategoriesWriterService $csvCategoriesWriterService
    ) {
        $this->kernelInterface = $kernelInterface;
        $this->csvGoodsReaderService = $csvGoodsReaderService;
        $this->csvRulesReaderService = $csvRulesReaderService;
        $this->sorterService = $sorterService;
        $this->csvCategoriesWriterService = $csvCategoriesWriterService;
    }

    public function proceedFile(UploadedFile $goodsCsv): void
    {
        $goods = $this->csvGoodsReaderService->read($goodsCsv->getPathname());
        $rules = $this->csvRulesReaderService->read($this->getRulesFilePath());

        $categories = $this->sorterService->sort($goods, $rules);

        $this->csvCategoriesWriterService->write($categories);
    }

    private function getRulesFilePath(): string
    {
        return sprintf(self::RULES_FILE_PATH_PATTERN, $this->kernelInterface->getProjectDir());
    }
}