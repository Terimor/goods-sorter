<?php

namespace App\Facade;

use App\Service\CsvCategoriesReportWriterService;
use App\Service\CsvCategoriesWriterService;
use App\Service\CsvGoodsReaderService;
use App\Service\CsvRulesReaderService;
use App\Service\SorterService;
use App\Service\CsvContainersReportWriterService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class SortFacade
{
    private const RULES_FILE_PATH_PATTERN = '%s/var/rules/category-rules.csv';
    private const REPLACEMENT_RULES_FILE_PATH_PATTERN = '%s/var/rules/replacement-rules.csv';

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

    /** @var CsvCategoriesReportWriterService $csvCategoriesReportWriterService */
    private $csvCategoriesReportWriterService;

    /** @var CsvContainersReportWriterService $csvContainersReportWriterService */
    private $csvContainersReportWriterService;

    public function __construct(
        KernelInterface $kernelInterface,
        CsvGoodsReaderService $csvGoodsReaderService,
        CsvRulesReaderService $csvRulesReaderService,
        SorterService $sorterService,
        CsvCategoriesWriterService $csvCategoriesWriterService,
        CsvCategoriesReportWriterService $csvCategoriesReportWriterService,
        CsvContainersReportWriterService $csvContainersReportWriterService
    ) {
        $this->kernelInterface = $kernelInterface;
        $this->csvGoodsReaderService = $csvGoodsReaderService;
        $this->csvRulesReaderService = $csvRulesReaderService;
        $this->sorterService = $sorterService;
        $this->csvCategoriesWriterService = $csvCategoriesWriterService;
        $this->csvCategoriesReportWriterService = $csvCategoriesReportWriterService;
        $this->csvContainersReportWriterService = $csvContainersReportWriterService;
    }

    public function proceedFile(UploadedFile $goodsCsv): void
    {
        $goods = $this->csvGoodsReaderService->read($goodsCsv->getPathname(), $this->getReplacementRulesFilePath());
        $rules = $this->csvRulesReaderService->read($this->getRulesFilePath());

        $categories = $this->sorterService->sort($goods, $rules);

        $this->csvCategoriesWriterService->write($categories);
        $this->csvCategoriesReportWriterService->write($categories);
        $this->csvContainersReportWriterService->write($categories);
    }

    private function getRulesFilePath(): string
    {
        return sprintf(self::RULES_FILE_PATH_PATTERN, $this->kernelInterface->getProjectDir());
    }

    private function getReplacementRulesFilePath(): string
    {
        return sprintf(self::REPLACEMENT_RULES_FILE_PATH_PATTERN, $this->kernelInterface->getProjectDir());
    }
}