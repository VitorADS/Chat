<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

trait Timestamps
{
    #[ORM\Column(nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    public DateTime $createdAt {
        get => $this->createdAt;
        set => $this->createdAt = $value;
    }

    #[ORM\Column(nullable: true)]
    public ?DateTime $updatedAt {
        get => $this->updatedAt;
        set => $this->updatedAt = $value;
    }

    #[ORM\Column(nullable: true)]
    private ?DateTime $deletedAt {
        get => $this->deletedAt;
        set => $this->deletedAt = $value;
    }
}