<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     graphql={
 *       "create"={"security"="is_granted('ROLE_ADMIN')"},
 *       "update"={"security"="is_granted('ROLE_ADMIN')"},
 *       "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\VersionRepository")
 */
class Version
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homepage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $license;

    /**
     * @ORM\Column(type="json")
     */
    private $authors = [];

    /**
     * @ORM\Column(type="json")
     */
    private $extra = [];

    /**
     * @ORM\Column(type="json")
     */
    private $requireSection = [];

    /**
     * @ORM\Column(type="json")
     */
    private $autoload = [];

    /**
     * @ORM\Column(name="autoload_dev", type="json")
     */
    private $autoloadDev = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    private $changelog;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\Column(name="composer_json", type="json", nullable=true)
     *
     * @var array|null
     */
    private $composerJson;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Package", inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $package;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getRequireSection(): ?array
    {
        return $this->requireSection;
    }

    public function setRequireSection(array $requireSection): self
    {
        $this->requireSection = $requireSection;

        return $this;
    }

    public function addRequire(string $package, string $versionConstraint): self
    {
        $this->requireSection[$package] = $versionConstraint;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): self
    {
        $this->package = $package;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    public function setHomepage($homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license): void
    {
        $this->license = $license;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    public function getChangelog(): ?string
    {
        return $this->changelog;
    }

    public function setChangelog(?string $changelog): void
    {
        $this->changelog = $changelog;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getAutoload(): array
    {
        return $this->autoload;
    }

    public function setAutoload(array $autoload): void
    {
        $this->autoload = $autoload;
    }

    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    public function setAutoloadDev(array $autoloadDev): void
    {
        $this->autoloadDev = $autoloadDev;
    }

    public function getComposerJson(): ?array
    {
        return $this->composerJson;
    }

    public function setComposerJson(?array $composerJson): void
    {
        $this->composerJson = $composerJson;
    }

    public function toJson(): array
    {
        $json = [
            'version' => $this->version,
            'type' => $this->type,
            'autoload' => $this->autoload,
            'autoload-dev' => $this->autoloadDev,
            'description' => $this->description,
            'require' => $this->requireSection,
            'authors' => $this->authors,
            'extra' => $this->extra,
            'dist' => [
                'url' => 'foo',
                'type' => 'zip',
            ],
        ];

        if ($this->type === 'shopware-platform-plugin') {
            $json['replace'][$this->getComposerJson()['name']] = $this->version;
        }

        return $json;
    }
}
