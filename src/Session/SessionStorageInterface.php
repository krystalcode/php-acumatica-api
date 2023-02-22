<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for the Acumatica session storage.
 *
 * Acumatica requires maintaining a session cookie and using it in each request
 * for authentication. The session storage provides the methods to store and
 * retrieve the session cookies.
 *
 * This library is agnostic to the application that integrates with Acumatica
 * and it therefore does not provide a session storage implementation. It needs
 * to be provided by the application.
 *
 * Depending on the data storage used, it may be a good idea to store all
 * session values serialized instead of just the cookie value. We may be adding
 * more values, such as creation and expiration timestamps, instance or API ID
 * for applications that work with multiple Acumatica instances or APIs, or even
 * username for applications that may connect with multiple credentials on
 * behalf of different users.
 *
 * @see \KrystalCode\Acumatica\Api\Session\SessionInterface
 */
interface SessionStorageInterface
{
    /**
     * Sets the given session in the storage.
     *
     * If a session with the given ID already exists, it will be overridden.
     *
     * @param \Drupal\acumatica\Session $session
     *   The session to store.
     */
    public function set(SessionInterface $session);

    /**
     * Returns the cookie value for the given session ID.
     *
     * @param string $id
     *   The session ID.
     *
     * @return \Drupal\acumatica\Session\SessionInterface|null
     *   The session, or null if there is no session for the given ID.
     */
    public function get(string $id = self::SESSION_ID_DEFAULT): ?SessionInterface;

    /**
     * Returns whether a session with given ID exists.
     *
     * @param string $id
     *   The session ID.
     *
     * @return bool
     *   TRUE if the session exists, FALSE otherwise.
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
    public function delete(string $id = self::SESSION_ID_DEFAULT);

    /**
     * Returns the number of existing sessions.
     *
     * @return int
     *   The number of existing sessions.
     */
    public function getCount(): int;
}
