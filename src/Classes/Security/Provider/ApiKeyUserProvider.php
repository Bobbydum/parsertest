<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 3/10/16
 * Time: 11:08 AM
 */

namespace Rest\Classes\Security\Provider;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ApiKeyUserProvider implements UserProviderInterface
{

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        if(!isset($this->users[$username])) {
            throw new UsernameNotFoundException(
                sprintf('User %s not found', $username)
            );
        }

        return new User(
            $username,
            null,
            array('ROLE_USER')
        );
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }

    /**
     * @param string $apiKey
     * @return string
     */
    public function getUserNameForApiKey($apiKey) {
        if(!$userName = array_search($apiKey, $this->users)) {
            throw new UsernameNotFoundException(
                sprintf('Username for apikey %s not found', $apiKey)
            );
        }

        return $userName;
    }
}