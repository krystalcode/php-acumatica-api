<?php

declare(strict_types=1);

namespace KrystalCode\Acumatica\Api\Session;

use League\OAuth2\Client\Token\AccessToken;

/**
 * Default implementation of the access token Acumatica session.
 */
class AccessTokenSession extends SessionBase
{
    /**
     * Constructs a new AccessTokenSession object.
     *
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     *   The access token.
     * @param string $id
     *   The session ID.
     */
    public function __construct(
        protected AccessToken $accessToken,
        protected string $id = SessionInterface::SESSION_ID_DEFAULT
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }
}
