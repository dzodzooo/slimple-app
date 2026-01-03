<?php
declare(strict_types=1);
namespace App\Traits;
trait Identifiable
{
    protected int $id;
    protected \DateTime $createdAt;
    protected \DateTime $updatedAt;
    public function getId(): int
    {
        return $this->id;
    }
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}