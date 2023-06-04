<?php

namespace App\models;

use Delight\Auth\Auth;
use Medoo\Medoo;

class User
{
    private $auth;
    private $db;

    public function __construct (Auth $auth, Medoo $db)
    {
        $this->auth = $auth;
        $this->db   = $db;
    }

    public function getAll ($user_id)
    {
        $user = $this->db->select(
            'users',
            [
                '[>]users_profile' => ['id' => 'user_id']
            ],
            '*',
            [
                'ORDER' => [
                    'users.id' => 'DESC'
                ]
            ]
        );

        return $user;
    }

    public function getInfo ($user_id = null)
    {

        if (is_null($user_id)) {
            if ($this->auth->isLoggedIn()) {
                $user_id = $this->auth->getUserId();
            } else {
                return false;
            }
        }

        $user = $this->db->get(
            'users',
            [
                '[>]users_profile' => ['id' => 'user_id']
            ],
            '*',
            ['users.id[=]' => $user_id]
        );

        $user['id'] = $user_id;
        $user['social'] = json_decode($user['social'], true);

        return $user;
    }

}