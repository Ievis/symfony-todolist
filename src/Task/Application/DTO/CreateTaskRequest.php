<?php

declare(strict_types=1);

namespace Task\Application\DTO;

use App\Common\Infrastructure\Attribute\ValidatedRequest;
use Symfony\Component\Validator\Constraints as Assert;

#[ValidatedRequest]
class CreateTaskRequest
{
    #[Assert\NotBlank(message: 'Поле name обязательно для заполнения')]
    #[Assert\Type('string')]
    private ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
}