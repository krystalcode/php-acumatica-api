<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\Session;

/**
 * Base class that facilitates Acumatica session implementations.
 */
abstract class SessionBase implements SessionInterface
{
    /**
     * Constructs a new Session object.
     *
     * @param string $id
     *   The session ID.
     */
    public function __construct(
        protected string $id = SessionInterface::SESSION_ID_DEFAULT
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }
}
