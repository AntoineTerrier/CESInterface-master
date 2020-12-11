<?php

namespace App\Security;

use App\Security\User;
use Symfony\Component\HttpClient\HttpClient;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.

        return $this->giveUser($username);
        
        // throw new \Exception('TODO: fill in loadUserByUsername() inside '.__FILE__);
    }

    private function giveUser(string $username)
    {
        $client = HttpClient::create();

        try {
            $response = $client->request('GET', 'http://localhost:3000/users/login/' . $username);
        } catch (\Throwable $th) {
            throw $th;
        }
        
        if ($response->getStatusCode() === 200) {
            $user = new User();
            $content = $response->toArray();
            $user->setEmail($content['email'])
                 ->setPassword($content['password'])
                 ->setFirstName($content['firstname'])
                 ->setLastName($content['lastname'])
                 ->setCampus($content['campus'])
                 ->setRank($content['role'])
                 ->setId($content['id']);
        }
        if ($user !== null) {
            return $user;
        }
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        $username = $user->getUsername();
        return $this->giveUser($username);

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
       // throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
