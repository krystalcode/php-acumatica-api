<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\Discovery;

use KrystalCode\ApiIterator\ClientInterface as IteratorClientInterface;
use KrystalCode\ApiIterator\Iterator as ApiIterator;

/**
 * Proxy for Acumatica API objects.
 *
 * The OpenAPI 2.0 definitions prefix the operation ID with the API ID. For
 * example, `SalesOrder_GetById`. As a consequence, the generated API objects
 * have the API ID prefixed in all methods e.g. `salesOrderGetById`. This is
 * inconvenient, potentially error prone, and it makes it more difficult to
 * write code that applies the same operations on different resources.
 *
 * We therefore use a proxy object that allows calling the operation by its ID
 * instead e.g. `getById` instead.
 *
 * Moreover, we add a `list` method that integrates with the API iterator that
 *  makes it easy to browse multiple pages of entities fetched using `getList`
 * endpoints. Thus, this class acts as a decorator as well in that it adds
 * functionality. For now it is fine to keep both here; if this further expands
 * we may want to take this off to a separate decorator class.
 */
class ClientProxy implements IteratorClientInterface
{
    /**
     * Constructs a new ClientProxy object.
     *
     * @param object $client
     *   The API client.
     * @param string $resourceId
     *   The ID of the resource that the client operates on. First letter is
     *   expected to be lowercase e.g. `salesOrder`.
     */
    public function __construct(
        protected object $client,
        protected string $resourceId
    ) {
    }

    /**
     * Calls the appropriate method adding the API ID, if not provided.
     *
     * @param string $name
     *   The method being called.
     * @param mixed $arguments
     *   The arguments to be passed to the method.
     *
     * @return mixed
     *   The results of the method call.
     *
     * @throws \InvalidArgumentException
     *   When the requested method was not found.
     */
    public function __call(string $name, mixed $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        }

        if (method_exists($this->client, $name)) {
            return $this->client->{$name}(...$arguments);
        }

        $methodName = $this->resourceId . ucfirst($name);
        if (method_exists($this->client, $methodName)) {
            return $this->client->{$methodName}(...$arguments);
        }

        throw new \InvalidArgumentException(sprintf(
            'Unknown method %s',
            $name
        ));
    }

    /**
     * {@inheritdoc}
     *
     * It is recommended to always request entities between two timestamps on
     * the `LastModified` field. Otherwise new entities can be added to the
     * results while iterating over the pages and resulting in some entities
     * appearing twice.
     *
     * @I Support caching pages in the iterator
     *    type     : improvement
     *    priority : low
     *    labels   : cache, performance
     */
    public function list(array $options = [], array $query = [])
    {
        $options = array_merge(
            [
                'page' => 1,
                'limit' => 100,
                'delay' => null,
            ],
            $options
        );
        $query = array_merge(
            [
                'select' => null,
                'filter' => null,
                'expand' => null,
                'custom' => null,
            ],
            $query
        );
        $query['skip'] = ($options['page'] - 1) * $options['limit'];
        $query['top'] = $options['limit'];

        // By default, we return an iterator. Caller code can then loop over it
        // and the iterator will internally handle making the API calls for
        // fetching the objects for each page.
        if (empty($options['bypass_iterator'])) {
            return new ApiIterator(
                $this,
                $options['page'],
                $options['limit'],
                $query,
                false,
                $options['delay']
            );
        }

        // Otherwise, either it is requested to fetch the results directly, or
        // this is a call by the iterator while being looped over. Make the
        // request and return the results.
        unset($options['bypass_iterator']);

        $objects = $this->getList(
            $query['select'],
            $query['filter'],
            $query['expand'],
            $query['custom'],
            $query['skip'],
            $query['top']
        );
        if ($objects === null) {
            $objects = [];
        }

        $has_more = null;
        $count = count($objects);
        if ($count === 0 || $count < $options['limit']) {
            $has_more = false;
        }

        return [
            new \CachingIterator(
                new \ArrayIterator($objects),
                \CachingIterator::FULL_CACHE
            ),
            $has_more,
            $query,
        ];
    }
}
