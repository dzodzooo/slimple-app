<?php
namespace App\Contracts;
interface SessionInterface
{
    public function start();
    public function set(string $name, mixed $value): bool;
    public function write_and_end();
    public function get(string $name);
    public function count(): int;
}