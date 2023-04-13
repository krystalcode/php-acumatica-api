<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\OData;

/**
 * An OData v3 filter.
 *
 * When Acumatica will support OData v4 integrations may be able to use an open
 * source library that fully implements the OData protocol. For now, we provide
 * simple utilities to make building simple filters easier.
 *
 * A filter is a clause on a specific property added to the `$filter` URL
 * parameter as defined by OData v3.
 */
class Filter implements \Stringable
{
    /**
     * Constructs a new Filter object.
     *
     * @param string $key
     *   The key of the filter property.
     * @param string $value
     *   The value of the filter property.
     * @param string $operator
     *   The operator.
     */
    public function __construct(
        protected string $key,
        protected string $value,
        protected string $operator
    ) {
        $this->validateOperator();
    }

    /**
     * Returns the filter as string as required by the client.
     *
     * Joins the propery key and value by the operator.
     *
     * @return string
     *   The string value for the filter.
     */
    public function __toString(): string
    {
        return implode(
            ' ',
            [
                $this->key,
                $this->operator,
                $this->value,
            ]
        );
    }

    /**
     * Validates that the filter's operator is valid.
     *
     * @throws \InvalidArgumentException
     *   If the filter's operator is invalid.
     */
    protected function validateOperator(): void
    {
        $operators = [
            'eq',
            'ne',
            'ge',
            'gt',
            'lt',
            'le',
            'not',
            'has',
        ];
        if (in_array($this->operator, $operators)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'Invalid filter group operator "%s". Valid operators are "%s".',
            $this->operator,
            implode(', ', $operators)
        ));
    }
}
