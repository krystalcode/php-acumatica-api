<?php

namespace KrystalCode\Acumatica\Api\Session;

/**
 * The interface for the Acumatica session storages that support expiration.
 *
 * Acumatica cleans up sessions that are not used for a certain period of time.
 * Access tokens expire after some time as well and Acumatica rejects requests
 * that use expired tokens. For access tokens - that is the currently supported
 * authentication method - we also check whether a given token has expired
 * before issuing requests and we request a new one if it has.
 *
 * When possible, however, applications should be either deleting sessions when
 * they finished with their requests, or setting them to expire at the end of
 * each call for cookie authentication or at the expiration time given by
 * Acumatica for access tokens. This way, next call will know whether there is a
 * valid session and create a new one if not, instead of performing unnecessary
 * validations or calls using an expired session and handling the rejecting
 * response before creating a new one.
 *
 * This interface only provides a method for setting a session to expire. How
 * the expiration is actually done highly depends on the type of storage and the
 * application; it is therefore left for the actual storage implementation.
 *
 * The statement that "applications should be either deleting sessions when they
 * finished with their requests, or setting them to expire" might not be true if
 * the application maintains only one access token for all its requests i.e. one
 * session, authorized with the `api` scope. In such cases there is no risk of
 * hitting session limits and it is not necessary to delete sessions.
 *
 * @see \KrystalCode\Acumatica\Api\Session\SessionInterface
 */
interface SupportsExpirationSessionStorageInterface
{
    /**
     * Schedules the session with the given ID for expiration.
     *
     * Expired sessions that may be cleaned up after they have expired should
     * not be returned when the requesting the session with the default getter
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
