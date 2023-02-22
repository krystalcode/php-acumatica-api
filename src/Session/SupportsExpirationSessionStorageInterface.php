<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for the Acumatica session storages that support expiration.
 *
 * Acumatica cleans up sessions that are not used for a certain period of time.
 * When possible, however, applications should be either deleting session when
 * they finished with their requests, or setting them to expire at the end of
 * each call. This way, next call will know whether there is a valid session and
 * create a new one if not, instead of making an unnecessary call using an
 * expired session and handling the rejecting response, before creating a new
 * one.
 *
 * This interface only provides a method for setting a session to expire. How
 * the expiration is actually done highly depends on the type of storage and it
 * is therefore left for the actual implementation.
 *
 * @see \KrystalCode\Acumatica\Api\Session\SessionInterface
 */
interface SupportsExpirationSessionStorageInterface
{
    /**
     * Schedules the session with the given ID for expiration.
     *
     * Expired sessions that may be cleaned up after they have expired should
     * not be return when the requesting the session with the default getter
     * method.
     *
     * This method does not need to return anything nor throw an exception if
     * there is no session with the given ID.
     *
     * @param string $id
     *   The session ID.
     * @param int $interval
     *   The time interval in seconds from the present moment that the session
     *   should expire at.
     */
    public function expire(
        string $id = self::SESSION_ID_DEFAULT,
        int $interval = 0
    );
}
