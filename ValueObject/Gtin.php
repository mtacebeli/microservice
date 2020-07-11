<?php

declare(strict_types=1);

namespace Microservice\ValueObject;

/**
 * Class Gtin
 */
class Gtin
{
    /** @var string */
    private $value;

    /**
     * Gtin constructor.
     *
     * @param string $gtin
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $gtin)
    {
        $this->validateLength($gtin);
        $this->validateChecksum($gtin);

        $this->value = $gtin;
    }

    /**
     * @return string
     */
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

    public function equalTo(Gtin $gtin): bool
    {
        return $this->value === $gtin->value();
    }

    /**
     * @param string $gtin
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    private function validateLength(string $gtin): void
    {
        if (!in_array(mb_strlen($gtin), [8, 12, 13, 14, 17, 18], true)) {
            throw new \InvalidArgumentException(sprintf('Gtin length is invalid. "%d"', $gtin));
        }
    }

    /**
     * @param string $gtin
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    private function validateChecksum(string $gtin): void
    {
        /** @var int[] $gtinElements */
        $gtinElements = mb_str_split($gtin);

        $givenChecksum = array_pop($gtinElements);
        if (!is_numeric($givenChecksum)) {
            throw new \InvalidArgumentException(sprintf('Gtin contains non numeric character %s', $givenChecksum));
        }

        $gtinElements = array_reverse($gtinElements);
        $sum = 0;

        foreach ($gtinElements as $position => $element) {
            if (!is_numeric($element)) {
                throw new \InvalidArgumentException(sprintf('Gtin contains non numeric character %s', $element));
            }
            $factor = ($position % 2) ? 1 : 3; // even : odd
            $sum += $factor * $element;
        }

        $calculatedChecksum = (10 - ($sum % 10)) % 10;

        if (((int)$givenChecksum) !== $calculatedChecksum) {
            throw new \InvalidArgumentException(sprintf('Gtin checksum does not match. "%d"', $gtin));
        }
    }
}
