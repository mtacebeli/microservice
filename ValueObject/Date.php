<?php

declare(strict_types=1);

namespace Microservice\ValueObject;

/**
 * OrderDate - Auftragsdatum
 */
class Date implements \JsonSerializable
{
    private const DATE_FORMAT = 'Y-m-d';

    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->assertOrderDate($value);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param Date $date1
     * @param Date $date2
     *
     * @return bool
     */
    public static function equals(Date $date1, Date $date2): bool
    {
        return $date1->value() === $date2->value();
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return Date
     */
    public static function fromDateTime(\DateTimeInterface $dateTime): Date
    {
        return new static($dateTime->format(self::DATE_FORMAT));
    }

    /**
     * @param Date $date
     *
     * @return \DateTimeImmutable
     */
    public static function toDateTime(Date $date)
    {
        $dateTime = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date->value());

        if ($dateTime === false) {
            throw new \RuntimeException('invalid date format');
        }

        return $dateTime->setTime(0, 0);
    }

    /**
     * Get value
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    private function assertOrderDate($value): void
    {
        $this->validateDate($value);
    }

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    private function validateDate(string $value): void
    {
        $date = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $value);
        if (!$date || $date->format(self::DATE_FORMAT) !== $value) {
            $message = sprintf("Invalid date '%s', allowed format is %s", $value, self::DATE_FORMAT);

            throw new \InvalidArgumentException($message);
        }
    }
}
