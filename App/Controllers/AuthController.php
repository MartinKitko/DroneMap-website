<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
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
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\ViewResponse
     */
    public function register(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        if (isset($formData['submit'])) {
            $username = trim($this->request()->getValue("username"));
            if (empty($username)) {
                throw new Exception("Nezadané žiadne používateľské meno");
            }
            $user = User::getAll("username = ?" ,[$username])[0] ?? null;
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
        return $this->redirect('?c=markers&a=login');
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
}