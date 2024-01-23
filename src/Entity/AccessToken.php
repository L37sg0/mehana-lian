<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\Clock\now;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
class AccessToken
{
    public const SCOPE_MENU = 'menu';
    public const SCOPE_MENU_ITEMS = 'menu_items';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column]
    private ?string $iat = null;

    #[ORM\Column]
    private ?string $exp = null;

    #[ORM\Column]
    private array $scopes = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getIat(): ?string
    {
        return $this->iat;
    }

    public function setIat(string $iat): static
    {
        $this->iat = $iat;

        return $this;
    }

    public function getExp(): ?string
    {
        return $this->exp;
    }

    public function setExp(string $exp): static
    {
        $this->exp = $exp;

        return $this;
    }

    public function getScopes(): array
    {
        $scopes = $this->scopes;
        $scopes[] = self::SCOPE_MENU;
        $scopes[] = self::SCOPE_MENU_ITEMS;

        return array_unique($scopes);
    }

    public function setScopes(array $scopes): static
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function isExpired(): bool
    {
        return false;
    }
}
