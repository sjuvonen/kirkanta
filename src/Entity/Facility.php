<?php

namespace App\Entity;

use App\Entity\Feature\GroupOwnership;
use App\Entity\Feature\ModifiedAwareness;
use App\Entity\Feature\Sluggable;
use App\Entity\Feature\StateAwareness;
use App\Entity\Feature\Translatable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="organisations")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({
 *     "foreign" = "ServicePoint",
 *     "library" = "Library",
 *     "meta" = "MetaFacility",
 *     "mobile_stop" = "MobileStop",
 *     "organisation" = "Organisation",
 * })
 */
abstract class Facility extends EntityBase implements GroupOwnership, ModifiedAwareness, Sluggable, StateAwareness, Translatable
{
    use Feature\GroupOwnershipTrait;
    use Feature\ModifiedAwarenessTrait;
    use Feature\SluggableTrait;
    use Feature\StateAwarenessTrait;
    use Feature\TranslatableTrait;

    /**
     * @ORM\OneToMany(targetEntity="LibraryData", mappedBy="entity", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EXTRA_LAZY", indexBy="langcode")
     */
    protected $translations;

    public function __construct()
    {
        parent::__construct();
        $this->translations = new ArrayCollection;
    }
}
