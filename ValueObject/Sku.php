<?php

declare(strict_types=1);

namespace Microservice\ValueObject;

/**
 * Class SKU
 *
 * PHP Version 7
 */
class Sku
{
    /** @var string */
    private $value;

    /**
     * SKU constructor.
     *
     * @param string $sku
     */
    public function __construct(string $sku)
    {
        $this->assertSkuIsValid($sku);

        $this->value = $sku;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param int $modelCode
     * @param int $colorCode
     * @param int $sizeId
     *
     * @return SKU
     */
    public static function fromComponents(int $modelCode, int $colorCode, int $sizeId): SKU
    {
        return new self(sprintf('%d_%d_%d', $modelCode, $colorCode, $sizeId));
    }

    public function componentModelCode(): int
    {
        $components = explode('_', $this->value);

        return (int)$components[0];
    }

    public function componentColorCode(): int
    {
        $components = explode('_', $this->value);

        return (int)$components[1];
    }

    public function componentSizeId(): int
    {
        $components = explode('_', $this->value);

        return (int)$components[2];
    }

    /**
     * @param string $sku
     */
    private function assertSkuIsValid(string $sku): void
    {
        if (!preg_match('/^\d+_\d+_\d+$/', $sku)) {
            throw new \InvalidArgumentException('sku is not valid "' . $sku . '"');
        }
    }
}
