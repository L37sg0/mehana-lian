<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admins`')]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $totpSecret = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApiIntegration::class, orphanRemoval: true)]
    private Collection $apiIntegrations;

    public function __construct()
    {
        $this->apiIntegrations = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getTotpSecret(): ?string
    {
        return $this->totpSecret;
    }

    /**
     * @param $totpSecret
     * @return $this
     */
    public function setTotpSecret(string|null $totpSecret): self
    {
        $this->totpSecret = $totpSecret;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     * @return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function isTotpAuthenticationEnabled(): bool
    {
        return $this->totpSecret ? true : false;
    }

    public function getTotpAuthenticationUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getTotpAuthenticationConfiguration(): TotpConfigurationInterface|null
    {
        return new TotpConfiguration((string)$this->totpSecret, TotpConfiguration::ALGORITHM_SHA1, 30, 6);
    }

    /**
     * @return Collection<int, ApiIntegration>
     */
    public function getApiIntegrations(): Collection
    {
        return $this->apiIntegrations;
    }

    public function addApiIntegration(ApiIntegration $apiIntegration): static
    {
        if (!$this->apiIntegrations->contains($apiIntegration)) {
            $this->apiIntegrations->add($apiIntegration);
            $apiIntegration->setUser($this);
        }

        return $this;
    }

    public function removeApiIntegration(ApiIntegration $apiIntegration): static
    {
        if ($this->apiIntegrations->removeElement($apiIntegration)) {
            // set the owning side to null (unless already changed)
            if ($apiIntegration->getUser() === $this) {
                $apiIntegration->setUser(null);
            }
        }

        return $this;
    }
    
}
