<?php

namespace App\models;

class Helpres
{
    public static function message ($status, $message)
    {
        exit(json_encode([
            'method' => 'message',
            'status' => $status,
            'message' => $message
        ]));
    }

    public static function location ($url, $isSwal = 1, $title = 'Redirect...', $message = '', $timer = '1500')
    {
        exit(json_encode([
            'method' => 'url',
            'url' => $url,
            'isSwal' => $isSwal,
            'title' => $title,
            'message' => $message,
            'timer' => $timer
        ]));
    }

    public static function redirect ($url, $type = 1)
    {
        if ($type == 1) {
            header('location: /' . $url);
            exit;
        } else {
            echo '<script>setTimeout(\'location="/' . $url . '"\', 100)</script>';
        }
    }
}