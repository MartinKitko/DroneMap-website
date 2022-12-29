<?php

namespace App\Auth;

use App\Core\IAuthenticator;
use App\Models\User;

/**
 * Class Authenticator
 * Basic implementation of user authentication
 * @package App\Auth
 */
class Authenticator implements IAuthenticator
{
    /**
     * Authenticator constructor
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Verify, if the user is in DB and has his password is correct
     * @param $login
     * @param $password
     * @return bool
     * @throws \Exception
     */
    function login($login, $password): bool
    {
        $user = User::getAll("username = ?", [$login])[0] ?? null;
        if ($user == null) {
            throw new \Exception("Používateľ so zadaným menom neexistuje");
        }
        if (password_verify($password, $user->getPasswordHash())) {
            $_SESSION['user'] = $login;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logout the user
     */
    function logout() : void
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
            session_destroy();
        }
    }

    /**
     * Get the name of the logged-in user
     * @return string
     */
    function getLoggedUserName(): string
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : throw new \Exception("User not logged in");
    }

    /**
     * Get the context of the logged-in user
     * @return string
     */
    function getLoggedUserContext(): mixed
    {
        return null;
    }

    /**
     * Return if the user is authenticated or not
     * @return bool
     */
    function isLogged(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user'] != null;
    }

    /**
     * Return the id of the logged-in user
     * @return int
     */
    function getLoggedUserId(): int
    {
        if (!$this->isLogged()) {
            throw new \Exception("User not logged in");
        }
        $username = $_SESSION['user'];
        $user = User::getAll("username = ?", [$username])[0];
        return $user->getId();
    }
}