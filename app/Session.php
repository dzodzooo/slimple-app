<?php
namespace App;

use App\Contracts\SessionInterface;
class Session implements SessionInterface
{
    public int $counter;
    public function __construct()
    {
        $this->counter = 0;
    }
    public function start()
    {
        session_start();
    }

    public function set(string $name, mixed $value): bool
    {
        $this->counter++;
        if (isset($_SESSION)) {
            $_SESSION[$name] = $value;
            return true;
        }
        return false;
    }
    public function write_and_end()
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
    public function count(): int
    {
        return $this->counter;
    }
}