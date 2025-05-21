<?php

namespace Lib\Authentication;

use App\Models\User;

class Auth
{
    public static function login($user): void
    {
        $_SESSION['user']['id'] = $user->getId();
    }

    public static function user(): ?User
    {
        if (isset($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            return User::findById($id);
        }

        return null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']['id']) && self::user() !== null;
    }

    public static function checkRole(): bool
    {
        if(isset($_SESSION['user']['id']) && self::user()->getRole() == "PROFESSOR" || self::user()->getRole() == "MODERATOR") {
          return true;
        } else
          return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']['id']);
    }
}
