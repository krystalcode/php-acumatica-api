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
 * A filter joins multiple clauses on properties in a way that can they can be
 * added to the `$filter` URL parameter as defined by OData v3.
 */
class FilterGroup implements \Stringable
{
    /**
     * Constructs a new FilterGroup object.
     *
     * @param string $operator
     *   The operator.
     * @param \KrystalCode\Acumatica\Api\OData\Filter[] $filters
     *   An array of filters.
     */
    public function __construct(
        protected string $operator,
        protected array $filters = []
    ) {
        $this->validateOperator();
        $this->validateFilters();
    }

    /**
     * Returns the filter group as string as required by the client.
     *
     * Joins all filters by the operator.
     *
     * @return string
     *   The string value for the filter group.
     */
    public function __toString(): string
    {
        return array_reduce(
            $this->filters,
            function ($carry, $filter) {
                if ($carry === '') {
                    return (string) $filter;
                }
                return $carry . ' ' . $this->operator . ' ' . ((string) $filter);
            },
            ''
        );
    }

    /**
     * Adds a filter.
     *
     * @param \KrystalCode\Acumatica\Api\OData\Filter $filter
     *   The filter to add.
     */
    public function add(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Validates that the filter group's operator is valid.
     *
     * @throws \InvalidArgumentException
     *   If the filter group's operator is invalid.
     */
    protected function validateOperator(): void
    {
        $operators = [
            'and',
            'or',
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

    /**
     * Validates that the filters are objects of the expected type.
     *
     * @throws \InvalidArgumentException
     *   If one or more filters are invalid.
     */
    protected function validateFilters(): void
    {
        foreach ($this->filters as $filter) {
            if ($filter instanceof Filter) {
                continue;
            }

            $type = gettype($filter);
            throw new \InvalidArgumentException(
                'Filters must be objects of type "\KrystalCode\Acumatica\Api\OData\Filter", "%s" given.',
                $type === 'object' ? get_class($filter) : $type
            );
        }
    }
}
