<?php

namespace App\controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;

class HomeController
{

    private $template;
    private $pdo;

    public function __construct (\PDO $pdo, Engine $engine) {
        $this->pdo = $pdo;
        $this->templates = $engine;
    }

    public function index (Auth $auth)
    {
        echo $this->templates->render(
            'home',
            ['isLogged' => $auth->isLoggedIn()]
        );
    }

}