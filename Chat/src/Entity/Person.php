<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use App\Traits\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'app', name: 'person')]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Person extends AbstractEntity
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $name {
        get => $this->name;
        set => $this->name = $value;
    }

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    public \DateTimeInterface $birthdate {
        get => $this->birthdate;
        set => $this->birthdate = $value;
    }

    #[ORM\Column(length: 2)]
    private string $gender {
        get => $this->gender;
        set => $this->gender = $value;
    }

    #[ORM\OneToOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    private ?User $user {
        get => $this->user;
        set => $this->user = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
