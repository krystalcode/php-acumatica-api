<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for Acumatica sessions.
 *
 * Acumatica requires maintaining a session cookie or access token and using it
 * in each request for authentication. Applications can either use one single
 * session, or - for more complex cases - create multiple sessions to facilitate
 * different processes.
 *
 * Sessions unused for a certain period of time will be cleaned up by Acumatica,
 * while access tokens expire after a given time as well. However, it is
 * recommended that sessions are properly closed and deleted/expired in
 * storage after the set of calls that it is opened for has finished. Leaving
 * sessions open can result in hitting session rate limits.
 *
 * See the session storage for more on deleting/expiring sessions.
 *
 * The session ID/cookie/token values should not be changed after the session is
 * created. If a session expires and a new one is needed with the same ID, a new
 * session object should be created because it is essentially a new session. No
 * setter methods are therefore foreseen by the interface. Implementations
 * should be requiring the values in their constructors.
 *
 * An exception is when working with access tokens but maintaining one session
 * only; in that case the access token may be refreshed for the same session
 * instead of creating a new one. Thus the access token session interface and
 * implementation does provide a setter for the access token.
 *
 * @see \KrystalCode\Acumatica\Api\Session\SessionStorageInterface
 * @see \KrystalCode\Acumatica\Api\Session\SupportsExpirationSessionStorageInterface
 * @see \KrystalCode\Acumatica\Api\Session\AccessTokenSessionInterface
 */
interface SessionInterface
{
    /**
     * The default session ID.
     *
     * Provided for convenience for not needing to generate session IDs for
     * applications that do not need to maintain multiple sessions.
     * Implementations can use it as the default value.
     *
     * Application that do work with multiple sessions should not use this ID.
     * Rather, they should create their own session ID patterns.
     */
    public const SESSION_ID_DEFAULT = 'default';

    /**
     * Returns the session ID.
     *
     * @return string
     *   The session ID.
     */
    public function getId(): string;
}
