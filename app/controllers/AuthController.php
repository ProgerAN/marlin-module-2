<?php

namespace App\controllers;

use App\models\Helpres;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Medoo\Medoo;

class AuthController
{

    private $template;
    private $pdo;

    public function __construct (\PDO $pdo, Engine $engine) {
        $this->pdo = $pdo;
        $this->template = $engine;
    }

    public function login (Auth $auth)
    {
        if ($auth->isLoggedIn())
            Helpres::redirect('dashboard');

        echo $this->template->render('auth/login');
    }

    public function logger (Auth $auth)
    {

        try {
            $auth->login($_POST['email'], $_POST['password']);


            Helpres::location('dashboard', 1, 'Успешная авторизация', 'Происходит перенаправление в кабинет');
            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Helpres::message('Ошибка', 'Email адрес не найден в системе');
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Helpres::message('Ошибка', 'Неверный пароль');
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            Helpres::message('Ошибка', 'Электронная почта не подтверждена');
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Helpres::message('Ошибка', 'Слишком много запросов');
            die('Too many requests');
        }
    }

    public function registration (Auth $auth)
    {
        if ($auth->isLoggedIn())
            Helpres::redirect('dashboard');

        echo $this->template->render('auth/registration');
    }

    public function register (Auth $auth, Medoo $db)
    {

        try {
            $userId = $auth->register($_POST['email'], $_POST['password']);

            $db->insert("users_profile", [
                "user_id" => $userId
            ]);

            Helpres::location('login', 1, 'Успешная регистрация', 'Происходит перенаправление на страницу входа');
            echo 'We have signed up a new user with the ID ' . $userId;
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Helpres::message('Ошибка', 'Неправильный адрес электронной почты');
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Helpres::message('Ошибка', 'Недопустимый пароль');
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            Helpres::message('Ошибка', 'Пользователь уже существует');
            die('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Helpres::message('Ошибка', 'Слишком много запросов');
            die('Too many requests');
        }
    }

    public function logout (Auth $auth)
    {
        $auth->logOut();

        Helpres::redirect('login');
    }
}