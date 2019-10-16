<?php
namespace App\Entities;

use App\Interfaces\PresentableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\SubscriberFieldRepository");
 * @ORM\Table(name="subscriber_fields", uniqueConstraints={@ORM\UniqueConstraint(name="subscriber_field_unique_idx", columns={"field_id", "subscriber_id"})})
 */
class SubscriberField implements PresentableInterface
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
     * @ORM\ManyToOne(targetEntity="Field")
     * @ORM\JoinColumn(name="field_id")
     * @var Field
     */
    protected $field;

    /**
     * @ORM\ManyToOne(targetEntity="Subscriber")
     * @ORM\JoinColumn(name="subscriber_id")
     * @var Subscriber
     */
    protected $subscriber;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $value;

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
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field): void
    {
        $this->field = $field;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @param Subscriber $subscriber
     */
    public function setSubscriber(Subscriber $subscriber): void
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'field' => $this->field->present(),
        ];
    }
}
