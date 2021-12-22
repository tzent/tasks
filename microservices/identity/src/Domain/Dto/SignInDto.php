<?php

declare(strict_types=1);

namespace App\Domain\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class SignInDto
{
    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 6, max: 16)]
    private ?string $password;

    /**
     * @param string|null $email
     * @param string|null $password
     */
    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}