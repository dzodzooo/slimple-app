<?php
namespace App;

use App\Contracts\SessionInterface;
class Session implements SessionInterface
{
    public function start()
    {
        session_start();
    }

    public function regenerateId()
    {
        session_regenerate_id();
    }


    public function set(string $name, mixed $value): bool
    {
        if (isset($_SESSION)) {
            $_SESSION[$name] = $value;
            return true;
        }
        return false;
    }
    public function saveAndClose()
    {
        session_write_close();
    }

    public function get(string $name)
    {
        if (isset($_SESSION) and isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    public function reset()
    {
        session_unset();
        session_destroy();
    }

    public function hasStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    public function unset(string $name)
    {
        if (isset($_SESSION) and isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
}