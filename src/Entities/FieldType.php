<?php
namespace App\Entities;

use App\Interfaces\PresentableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\FieldTypeRepository");
 * @ORM\Table(name="field_types")
 */
class FieldType implements PresentableInterface
{
    const ID_BOOLEAN = '97466350-77bf-45b3-8c0b-d61b876f778b';
    const ID_DATE = '591aa34c-d1c0-41d6-99e4-85b6be44f90f';
    const ID_EMAIL = 'fee48e3d-ad51-439e-b17d-795f203bde79';
    const ID_NUMBER = '21af03fe-b044-4067-bb22-9feae069cb0e';
    const ID_STRING = '1f3f5943-0e39-435e-9ff6-9507f7778852';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="input_type")
     * @var string
     */
    protected $inputType;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $validators;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getInputType(): string
    {
        return $this->inputType;
    }

    /**
     * @param string $inputType
     */
    public function setInputType(string $inputType): void
    {
        $this->inputType = $inputType;
    }

    /**
     * @return string
     */
    public function getValidators(): string
    {
        return $this->validators;
    }

    /**
     * @param string $validators
     */
    public function setValidators(string $validators): void
    {
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'inputType' => $this->inputType,
        ];
    }
}
