<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\Discovery;

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
 */
class ClientProxy
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
}
