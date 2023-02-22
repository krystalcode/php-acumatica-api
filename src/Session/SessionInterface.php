<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for Acumatica sessions.
 *
 * Acumatica requires maintaining a session cookie and using it in each request
 * for authentication. Applications can either use one single session, or - for
 * more complex cases - create multiple sessions to facilitate different
 * processes.
 *
 * Sessions unused for a certain period of time will be cleaned up by Acumatica.
 * However, it is recommended that they are properly closed and deleted/expired
 * in storage after the set of calls that it is opened for has finished.
 *
 * See the session storage for more on deleting/expiring sessions.
 *
 * The session ID/cookie values should not be changed after the session is
 * created. If a session expires and a new one is needed with the same ID, a new
 * session object should be created because it is essentially a new session. No
 * setter methods are therefore foreseen by the interface. Implementations
 * should be requiring the values in their constructors.
 */
interface SessionInterface
{
    /**
     * The default session ID.
     *
     * Provided for convenience for not needing to generate session IDs for
     * applications that do not need to maintain multiple sessions.
     * Implementations can use it as the default value.
     */
    public const SESSION_ID_DEFAULT = 'default';

    /**
     * Returns the session ID.
     *
     * @return string
     *   The session ID.
     */
    public function getId(): string;

    /**
     * Returns the cookie value.
     *
     * @return string
     *   The cookie value.
     */
    public function getCookie(): string;
}
