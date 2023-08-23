<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for Acumatica session storage implementations.
 *
 * Acumatica requires maintaining a session cookie or an access token and using
 * it in each request for authentication. The session storage provides the
 * methods to manage sessions with their cookies/tokens.
 *
 * This library is agnostic to the application that integrates with Acumatica
 * and the data stores it uses; it therefore does not provide a session storage
 * implementation. It needs to be provided by the application.
 *
 * Depending on the data store used, it may be a good idea to store all session
 * values serialized instead of just the cookie/token value. We may be adding
 * more values, such as creation and expiration timestamps, instance or API ID
 * for applications that work with multiple Acumatica instances or APIs, or even
 * username for applications that may connect with multiple credentials on
 * behalf of different users.
 *
 * This interface provides methods that should be transparent to the application
 * when it comes to session expiration i.e. all expired sessions should be
 * treated by these methods as if they don't exist. If the application needs to
 * be aware of expired sessions, the session storage needs to implement the
 * related interface.
 *
 * @see \KrystalCode\Acumatica\Api\Session\SessionInterface
 * @see \KrystalCode\Acumatica\Api\Session\SupportsExpirationSessionStorageInterface
 */
interface SessionStorageInterface
{
    /**
     * Sets the given session in the storage.
     *
     * If a session with the given ID already exists, it will be overridden.
     *
     * @param \KrystalCode\Acumatica\Api\Session\SessionInterface $session
     *   The session to store.
     */
    public function set(SessionInterface $session): void;

    /**
     * Returns the session for the given ID.
     *
     * `null` should be returned for expired sessions.
     *
     * @param string $id
     *   The session ID.
     *
     * @return \KrystalCode\Acumatica\Api\Session\SessionInterface|null
     *   The session, or `null` if there is no session for the given ID, or if
     *   there is a session but it has expired.
     */
    public function get(string $id = self::SESSION_ID_DEFAULT): ?SessionInterface;

    /**
     * Returns whether a session with given ID exists.
     *
     * `false` should be returned for expired sessions.
     *
     * @param string $id
     *   The session ID.
     *
     * @return bool
     *   `true` if the session exists, `false` otherwise.
     */
    public function exists(string $id = self::SESSION_ID_DEFAULT): bool;

    /**
     * Deletes the session with the given ID.
     *
     * This method does not need to return anything nor throw an exception if
     * there is no session with the given ID.
     *
     * @param string $id
     *   The session ID.
     */
    public function delete(string $id = self::SESSION_ID_DEFAULT): void;

    /**
     * Returns the number of existing sessions.
     *
     * Expired sessions should not be counted.
     *
     * @return int
     *   The number of existing sessions.
     */
    public function getCount(): int;
}
