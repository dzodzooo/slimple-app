<?php
namespace App\Contracts;
interface SessionInterface
{
    public function start();
    public function set(string $name, mixed $value): bool;
    public function unset(string $name);
    public function saveAndClose();
    public function get(string $name);
    public function reset();
    public function hasStarted(): bool;
}