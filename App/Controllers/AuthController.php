<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\Responses\JsonResponse;
use App\Core\Responses\Response;
use App\Models\User;
use Exception;

/**
 * Class AuthController
 * Controller for authentication actions
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /**
     *
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\Response
     */
    public function index(): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Register a user
     * @return \App\Core\Responses\ViewResponse
     */
    public function register(): Response
    {
        return $this->html([
            'user' => new User()
        ],
            'register'
        );
    }

    /**
     * Store a new user
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\ViewResponse
     * @throws Exception
     */
    public function store(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        if (isset($formData['submit'])) {
            $username = trim($this->request()->getValue("username"));
            if (empty($username)) {
                throw new Exception("Nezadané žiadne používateľské meno");
            }
            $user = User::getAll("username = ?", [$username])[0] ?? null;
            if ($user != null) {
                throw new Exception("Používateľské meno už niekto používa");
            } else {
                $user = new User();
            }
            $user->setUsername($username);
            $email = filter_var($this->request()->getValue("email"), FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception("Emailová adresa nie je platná");
            }
            $emailDB = User::getAll("email = ?", [$email])[0] ?? null;
            if ($emailDB != null) {
                throw new Exception("Zadaný email už niekto používa");
            }
            $user->setEmail($email);
            $password = $this->request()->getValue("password");
            $password2 = $this->request()->getValue("password2");
            if ($password != $password2) {
                throw new Exception("Zadané heslá sa nezhodujú");
            }
            $user->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));

            $user->save();
        }
        return $this->redirect("?c=auth&a=login");
    }

    /**
     * Login a user
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\ViewResponse
     */
    public function login(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;
        if (isset($formData['submit'])) {
            $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);
            if ($logged) {
                return $this->redirect('?c=markers');
            }
        }

        $data = ($logged === false ? ['message' => 'Zlý login alebo heslo!'] : []);
        return $this->html($data);
    }

    /**
     * Logout a user
     * @return \App\Core\Responses\ViewResponse
     */
    public function logout(): Response
    {
        $this->app->getAuth()->logout();
        return $this->redirect('?c=home');
    }

    /**
     * Check if the username is already taken
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function checkUsername(): Response
    {
        if (isset($_POST['username'])) {
            $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES);
            $users = User::getAll("username = ?", [$username]);
            return new JsonResponse(['taken' => count($users) > 0]);
        }

        return new JsonResponse(['error' => 'Nezadané žiadne meno']);
    }

    /**
     * Check if the email is already taken
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function checkEmail(): Response
    {
        if (isset($_POST['email'])) {
            $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES);
            if ($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return new JsonResponse(['notValid' => true]);
            }
            $users = User::getAll("email = ?", [$email]);
            return new JsonResponse(['taken' => count($users) > 0]);
        }

        return new JsonResponse(['error' => 'Nezadaný žiaden email']);
    }

    /**
     * Check if the email is already taken
     * @return \App\Core\Responses\JsonResponse
     */
    public function checkPasswords(): Response
    {
        if (isset($_POST['password']) and isset($_POST['password2'])) {
            $password = htmlspecialchars(trim($_POST['password']), ENT_QUOTES);
            if (strlen($password) < 8) {
                return new JsonResponse(['tooShort' => true]);
            }
            $password2 = htmlspecialchars(trim($_POST['password2']), ENT_QUOTES);
            return new JsonResponse(['same' => $password == $password2]);
        }

        return new JsonResponse(['error' => 'Nezadaný žiaden email']);
    }
}