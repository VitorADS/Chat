<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use App\Traits\Timestamps;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(schema: 'app', name: 'message')]
#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Message
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private User $origin {
        get => $this->origin;
        set => $this->origin = $value;
    }

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private User $recipient {
        get => $this->recipient;
        set => $this->recipient = $value;
    }

    #[ORM\OneToOne(inversedBy: 'message_content', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private MessageContent $messageContent {
        get => $this->messageContent;
        set => $this->messageContent = $value;
    }
}
