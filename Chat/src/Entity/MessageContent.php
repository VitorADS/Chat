<?php

namespace App\Entity;

use App\Repository\MessageContentRepository;
use App\Traits\Timestamps;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'app', name: 'message_content')]
#[ORM\Entity(repositoryClass: MessageContentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class MessageContent
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text {
        get => $this->text;
        set => $this->text = $value;
    }

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $midia {
        get => $this->midia;
        set => $this->midia = $value;
    }

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private Message $message {
        get => $this->message;
        set => $this->message = $value;
    }
}
