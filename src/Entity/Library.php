<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Library extends Facility implements LibraryInterface
{
    use LibraryTrait;

    /**
     * @ORM\OneToMany(targetEntity="LibraryPhoto", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"weight": "ASC", "id": "ASC"})
     */
    protected $pictures;

    /**
     * An array of nested photos.
     *
     * NOTE: Due to a limitation in Doctrine, this property has to be a raw array and not
     * a Collection nor other kind of object.
     *
     * @ORM\Column(type="json_document", options={"jsonb": true})
     */
    protected $photos;

    /**
     * Holds a reference to the Collection that manipulates the photos array.
     */
    private $_photos;

    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="library", cascade={"persist", "remove"})
     */
    protected $persons;

    /**
     * @ORM\OneToMany(targetEntity="Period", mappedBy="parent", cascade={"persist", "remove"}, indexBy="id")
     * @ORM\OrderBy({"valid_from" = "desc", "valid_until" = "desc"})
     */
    protected $periods;

    /**
     * @ORM\OneToMany(targetEntity="ServiceInstance", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $services;

    /**
     * @ORM\OneToMany(targetEntity="Department", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $departments;

    /**
     * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="libraries")
     */
    private $organisation;

    public function __construct()
    {
        parent::__construct();
        $this->services = new ArrayCollection;
        $this->departments = new ArrayCollection;
        $this->persons = new ArrayCollection;

        $this->accessibility = new ArrayCollection;
        $this->mobile_stops = new ArrayCollection;
        $this->periods = new ArrayCollection;
        $this->pictures = new ArrayCollection;

        $this->photos = [];
    }

    public function getDepartments() : Collection
    {
        return $this->departments;
    }

    public function getPersons() : Collection
    {
        return $this->persons;
    }

    public function getServices() : Collection
    {
        return $this->services;
    }

    public function getOrganisation() : ?Organisation
    {
        return $this->organisation;
    }

    public function getPhotos() : Collection
    {
        /**
         * Use a Collection object to preserve API compatibility with other collections.
         */

        if (!$this->photos) {
            // Empty collections are serialized to NULL in order to avoid the [] vs. {} issue.
            $this->photos = [];
        }

        if (!$this->_photos) {
          $this->_photos = new \App\Util\ProxyCollection($this->photos);
        }

        return $this->_photos;
    }

    public function test() {
        var_dump($this->photos);
    }
}
