<?php

namespace App\Entity;

use App\Entity\Feature\Translatable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="departments")
 */
class Department extends EntityBase implements Translatable
{
    use Feature\TranslatableTrait;

    /**
     * @ORM\column(type="string")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Library", inversedBy="departments")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Period", mappedBy="department", cascade={"persist", "remove"})
     */
    private $periods;

    /**
     * @ORM\OneToMany(targetEntity="PhoneNumber", mappedBy="department", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight": "ASC"})
     */
    private $phone_numbers;

    /**
     * @ORM\OneToMany(targetEntity="EmailAddress", mappedBy="department", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight": "ASC"})
     */
    private $email_addresses;

    /**
     * @ORM\OneToMany(targetEntity="WebsiteLink", mappedBy="department", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight": "ASC", "id": "ASC"})
     */
    private $links;

    /**
     * @ORM\OneToMany(targetEntity="DepartmentData", mappedBy="entity", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EXTRA_LAZY", indexBy="langcode")
     */
    private $translations;

    public function __toString()
    {
        return $this->getName();
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setType(string $type) : void
    {
        $this->type = $type;
    }

    public function getName() : string
    {
        return $this->translations[$this->langcode]->getName();
    }

    public function setName(string $name) : void
    {
        $this->translations[$this->langcode]->setName($name);
    }

    public function getDescription() : ?string
    {
        return $this->translations[$this->langcode]->getDescription();
    }

    public function setDescription(?string $description) : void
    {
        $this->translations[$this->langcode]->setDescription($description);
    }

    public function getLibrary() : Library
    {
        return $this->parent;
    }

    public function setLibrary(Library $library) : void
    {
        $this->parent = $library;
    }

    public function getParent() : Library
    {
        return $this->getLibrary();
    }

    public function setParent($parent) : void
    {
        $this->parent = $parent;
    }
}
