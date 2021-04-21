<?php


namespace App\Entity;


class CategoryGoods
{
    /** @var string $categoryName */
    private $categoryName;

    /** @var Good[] */
    private $goods = [];

    public function __construct(string $categoryName)
    {
        $this->categoryName = $categoryName;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function getGoods(): array
    {
        return $this->goods;
    }

    public function addGood(Good $good): void
    {
        $this->goods[] = $good;
    }

    public function getTotalAmount(): int
    {
        $total = 0;

        foreach ($this->goods as $good) {
            $total += $good->getTotal();
        }

        return $total;
    }

    public function getAmountWithoutVolume(): int
    {
        $total = 0;

        foreach ($this->goods as $good) {
            if (is_null($good->getTotal())) {
                $total++;
            }
        }

        return $total;
    }

    public function getContainersAmount(): array
    {
        $result = [];

        foreach ($this->goods as $good) {
            $volume = $good->getVolume();

            if ($volume && $volume->isLiters()) {
                if (!isset($result[$volume->getAmountWithUnits()])) {
                    $result[$volume->getAmountWithUnits()] = 0;
                }

                $result[$volume->getAmountWithUnits()] += $good->getAmount();
            }
        }

        return $result;
    }
}