<?php

namespace App\Routing;

use RuntimeException;
use App\EntityTypeManager;
use App\Controller\OrganisationController;
use App\Entity\Feature\StateAwareness;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class LibraryRouteLoader extends Loader
{
    private $loaded = false;
    private $types;

    public function __construct(EntityTypeManager $types)
    {
        $this->types = $types;
    }

    public function supports($resource, $type = null) : bool
    {
        return $type == 'library_routes';
    }

    public function load($resource, $type = null) : RouteCollection
    {
        if ($this->loaded) {
            throw new RuntimeException('Trying to load library routes again');
        }

        $routes = new RouteCollection;
        $derivatives = ['library', 'service_point'];
        $resources = ['departments', 'periods', 'persons', 'phone_numbers', 'pictures', 'services'];

        foreach ($derivatives as $type_id) {
            $base_path = "/{$type_id}/{{$type_id}}";

            $defaults = [
                'entity_type' => $type_id,
            ];

            $requirements = [
                $type_id => '\d+',
                'resource' => implode('|', $resources),
            ];

            $resource_collection = new Route("{$base_path}/{resource}", $defaults + [
                '_controller' => sprintf('%s:resourceCollection', OrganisationController::class)
            ], $requirements);

            $add_resource = new Route("{$base_path}/{resource}/add", $defaults + [
                '_controller' => sprintf('%s:addResource', OrganisationController::class)
            ], $requirements);

            $edit_resource = new Route("{$base_path}/{resource}/{resource_id}/edit", $defaults + [
                '_controller' => sprintf('%s:editResource', OrganisationController::class)
            ], $requirements + [
                'resource_id' => '\d+'
            ]);

            $delete_resource = new Route("{$base_path}/{resource}/{resource_id}/delete", $defaults + [
                '_controller' => sprintf('%s:editResource', OrganisationController::class)
            ], $requirements + [
                'resource_id' => '\d+'
            ]);

            $routes->add("entity.{$type_id}.resource_collection", $resource_collection);
            $routes->add("entity.{$type_id}.add_resource", $add_resource);
            $routes->add("entity.{$type_id}.edit_resource", $edit_resource);
            $routes->add("entity.{$type_id}.delete_resource", $delete_resource);
        }

        return $routes;
    }
}