<?php
namespace App\Entities;

use App\Interfaces\PresentableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\FieldRepository");
 * @ORM\Table(name="fields")
 */
class Field implements PresentableInterface
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
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $variable;

    /**
     * @ORM\ManyToOne(targetEntity="FieldType")
     * @ORM\JoinColumn(name="field_type_id", nullable=false)
     * @var FieldType
     */
    protected $fieldType;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $required;

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * @param string $variable
     */
    public function setVariable(string $variable): void
    {
        $this->variable = $variable;
    }

    /**
     * @return FieldType
     */
    public function getFieldType(): FieldType
    {
        return $this->fieldType;
    }

    /**
     * @param FieldType $fieldType
     */
    public function setFieldType(FieldType $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return string`
     */
    public function getValidatorRuleString(): string
    {
        $ruleString = "";

        if ($this->isRequired()) {
            $ruleString .= 'required|';
        }

        $ruleString .= $this->getFieldType()->getValidators();

        return $ruleString;
    }

    /**
     * @inheritDoc
     */
    public function present(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'variable' => $this->variable,
            'required' => $this->required,
            'fieldType' => $this->fieldType->present(),
        ];
    }
}
