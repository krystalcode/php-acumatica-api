<?php

namespace KrystalCode\Acumatica\Api\Session;

use League\OAuth2\Client\Token\AccessToken;

/**
 * The interface for Acumatica access token sessions.
 */
interface AccessTokenSessionInterface extends SessionInterface
{
    /**
     * Sets the access token.
     *
     * Normally the access token should be provided when creating a session. A
     * new access token would be considered a new session for Acumatica and a
     * new session object should be created. However, in cases where for the
     * purposes of the application it is considered that we maintain one session
     * and getting a new access token is considered as refreshing of the
     * existing session, we provide this setter so that the existing session can
     * be updated.
     *
     * This is mostly the case when working with the `api` scope without the
     * `api:concurrent` scope i.e. concurrent sessions are not enabled. When
     * working with concurrent sessions i.e. both `api` and `api:concurrent`
     * scopes, then you may want to create a new session instead of updating an
     * existing one.
     *
     * @param \League\OAuth2\Client\Token\AccessToken $accessToken
     *   The access token.
     */
    public function setAccessToken(AccessToken $accessToken): void;

    /**
     * Returns the access token.
     *
     * @return \League\OAuth2\Client\Token\AccessToken
     *   The access token.
     */
    public function getAccessToken(): AccessToken;
}
