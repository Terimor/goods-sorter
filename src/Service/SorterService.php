<?php


namespace App\Service;


use App\Entity\CategoryGoods;
use App\Entity\Good;
use App\Entity\Rule;

class SorterService
{
    private const UNSORTED_CATEGORY_NAME = 'Unsorted';

    public function sort(array $goods, array $rules): array
    {
        $sortedGoods = [];

        /** @var Rule $rule */
        foreach ($rules as $rule) {
            $categoryGoods = new CategoryGoods($rule->getCategoryName());

            /** @var Good $good */
            foreach ($goods as $key => $good) {
                if ($good->isRuleCorrespond($rule)) {
                    $categoryGoods->addGood($good);
                    unset($goods[$key]);
                }
            }

            $sortedGoods[] = $categoryGoods;
        }

        $unsortedCategory = new CategoryGoods(self::UNSORTED_CATEGORY_NAME);
        foreach ($goods as $unsortedGood) {
            if (isset($unsortedGood)) {
                $unsortedCategory->addGood($unsortedGood);
            }
        }
        $sortedGoods[] = $unsortedCategory;

        return $sortedGoods;
    }
}