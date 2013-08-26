# Full routing copying between Bundles

This feature usefull, when you need to create a full-copied site, which will run on different domain or subdomain, 
with some modifications.

To duplicate all routes of parent bundle, prepend in `routing.yml` these lines:

```yml
# app/config/routing.yml

MultipleInheritanceBundle:
    resource: .
    type: inheritance
```

Next, your child bundle class must implement `RoutingAdditionsInterface`:

```php
// src/Acme/ChildBundle/ChildBundle.php

use Igorynia\Bundle\MultipleInheritanceBundle\Routing\RoutingAdditionsInterface;

class ChildBundle extends Bundle implements RoutingAdditionsInterface
{

    /**
     * In this method you specify routes resources, that you need to duplicate
     */
    public function getResourcesToOverride()
    {
        return array(
            '@ParentBundle/Resources/config/routing.php',
        );
    }

    public function getParent()
    {
        return 'ParentBundle'; // Name of parent bundle
    }

    public function getRoutingPrefix()
    {
        return 'child';
    }

    public function getDefaults()
    {
        return array(); // This array will be merged with defaults of each route of parent bundle
    }

    public function getRequirements()
    {
        return array(); // This array will be merged with requirements of each route of parent bundle
    }

    public function getHost()
    {
        return 'test.example.com'; // Specifying domain restriction for routes. Leave it as empty string for disable host requirement
    }

}
```

All duplicated routes have prefix in names. Say, for route `home` in parent bundle, duplicated route name will be `child_home`.
