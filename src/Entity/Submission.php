<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use App\Controller\RetrieveSubmissionByToken;
/**
 * @ApiResource(
 *     collectionOperations={
 *       "get",
 *       "post"={"security"="is_granted('ROLE_USER')"},
 *       "retrieve_submission_by_token"={
 *         "method"="GET",
 *         "path"="/submission-by-token/{token}",
 *         "controller"=RetrieveSubmissionByToken::class,
 *     },
 *     },
 *     itemOperations={
 *       "get",
 *       "delete",
 *       "patch"={"security"="is_granted('ROLE_CANDIDATE')"},
 *       "put"={"security"="is_granted('ROLE_CANDIDATE')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SubmissionRepository")
 * @ApiFilter(SearchFilter::class, properties={"candidate": "exact"})
 */
class Submission
{
    public const STATUS_CREATED = 'created';
    public const STATUS_OPENED = 'opened';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REFUSED = 'refused';

    public static function getStatuses()
    {
        return [
            self::STATUS_CREATED,
            self::STATUS_OPENED,
            self::STATUS_PENDING,
            self::STATUS_SCHEDULED,
            self::STATUS_ACCEPTED,
            self::STATUS_REFUSED,
        ];
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wantedIncome;

    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    private $resume;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Choice(callback="getStatuses")
     */
    private $status = self::STATUS_CREATED;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="submissions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="submissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getPicture(): ?MediaObject
    {
        return $this->picture;
    }

    /**
     * @param MediaObject|null $picture
     * @return Submission
     */
    public function setPicture(?MediaObject $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getResume(): ?MediaObject
    {
        return $this->resume;
    }

    /**
     * @param MediaObject|null $resume
     * @return Submission
     */
    public function setResume(?MediaObject $resume): self
    {
        $this->resume = $resume;

        return $this;
    }


    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): self
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getWantedIncome(): ?int
    {
        return $this->wantedIncome;
    }

    public function setWantedIncome(int $wantedIncome): self
    {
        $this->wantedIncome = $wantedIncome;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getCandidate(): ?User
    {
        return $this->candidate;
    }

    public function setCandidate(?User $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
