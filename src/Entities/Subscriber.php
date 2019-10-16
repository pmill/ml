<?php
namespace App\Entities;

use App\Interfaces\PresentableInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\SubscriberRepository");
 * @ORM\Table(name="subscribers")
 */
class Subscriber implements PresentableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="SubscriberField", mappedBy="subscriber", cascade={"persist", "remove"})
     * @var ArrayCollection|SubscriberField[]
     */
    protected $subscriberFields;

    /**
     * @ORM\ManyToOne(targetEntity="SubscriberGroup")
     * @ORM\JoinColumn(name="subscriber_group_id", nullable=false)
     * @var SubscriberGroup
     */
    protected $subscriberGroup;

    /**
     * @ORM\ManyToOne(targetEntity="SubscriberState")
      * @ORM\JoinColumn(name="subscriber_state_id", nullable=false)
     * @var SubscriberState
     */
    protected $subscriberState;

    /**
     * Subscriber constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->subscriberFields = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return SubscriberField[]|ArrayCollection
     */
    public function getSubscriberFields()
    {
        return $this->subscriberFields;
    }

    /**
     * @param SubscriberField[]|ArrayCollection $subscriberFields
     */
    public function setSubscriberFields($subscriberFields): void
    {
        $this->subscriberFields = $subscriberFields;
    }

    /**
     * @param SubscriberField $subscriberField
     */
    public function addSubscriberField(SubscriberField $subscriberField)
    {
        $subscriberField->setSubscriber($this);

        $this->subscriberFields->add($subscriberField);
    }

    /**
     * @return SubscriberGroup
     */
    public function getSubscriberGroup(): SubscriberGroup
    {
        return $this->subscriberGroup;
    }

    /**
     * @param SubscriberGroup $subscriberGroup
     */
    public function setSubscriberGroup(SubscriberGroup $subscriberGroup): void
    {
        $this->subscriberGroup = $subscriberGroup;
    }

    /**
     * @return SubscriberState
     */
    public function getSubscriberState(): SubscriberState
    {
        return $this->subscriberState;
    }

    /**
     * @param SubscriberState $subscriberState
     */
    public function setSubscriberState(SubscriberState $subscriberState): void
    {
        $this->subscriberState = $subscriberState;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt->format('c'),
            'subscriberStateId' => $this->subscriberState->getId(),
            'subscriberStateName' => $this->subscriberState->getName(),
            'subscriberGroupId' => $this->subscriberGroup->getId(),
            'subscriberGroupName' => $this->subscriberGroup->getName(),
            'subscriberFields' => $this->subscriberFields->map(
                function (SubscriberField $subscriberField) {
                    return $subscriberField->present();
                }
            )->toArray(),
        ];
    }
}
