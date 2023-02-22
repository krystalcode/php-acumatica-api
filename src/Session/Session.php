<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\Session;

/**
 * Default implementation of an Acumatica session.
 */
class Session implements SessionInterface
{
    /**
     * The ID of the session.
     *
     * @var string
     */
    protected string $id;

    /**
     * The value of the session cookie.
     *
     * @var string
     */
    protected string $cookie;

    /**
     * Constructs a new Session object.
     *
     * @param string $cookie
     *   The cookie value.
     * @param string $id
     *   The session ID.
     */
    public function __construct(
        string $cookie,
        string $id = SessionInterface::SESSION_ID_DEFAULT
    ) {
        $this->cookie = $cookie;
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookie(): string
    {
        return $this->cookie;
    }
}
