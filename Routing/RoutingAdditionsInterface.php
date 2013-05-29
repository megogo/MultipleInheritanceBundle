<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Routing;


interface RoutingAdditionsInterface
{

    const ACTIVE_BUNDLE_ATTRIBUTE = '_active_bundle';

    /**
     * @return string Unique routing prefix
     */
    public function getRoutingPrefix();

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @return array
     */
    public function getRequirements();

    /**
     * @return string empty string by default
     */
    public function getHost();

    /**
     * @return array
     */
    public function getResourcesToOverride();

}