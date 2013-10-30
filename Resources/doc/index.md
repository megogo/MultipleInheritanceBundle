# Getting started with MultipleInheritanceBundle

## Quick start

If you are not familiar with Bundle Inheritance in Symfony, I suggest you to read [first](http://symfony.com/doc/master/cookbook/bundles/inheritance.html) and the [second](http://symfony.com/doc/master/cookbook/bundles/override.html) cookbok articles about that.

Limitation of native Symfony's Bundle Inheritance is only one-to-one child-parent relationships between bundles. MultipleInheritanceBundle aims to remove this limitation. To do this, plugin introduces the concept of *Active Bundle*. 

Active Bundle determines priority of searching and loading resources between child-parent bundles. Active Bundle is the bundle, the controller of which was called by the routing. For example, if your route, that matched request have something like `'_controller' => 'TestBundle:Controller:Action'`, `TestBundle` will be active until the end of the request. For different routes different bundles can be active.

## Installation

1) Add this bundle to your `composer.json` file:

```javascript
{
	"require": {
		"megogo/multiple-inheritance-bundle": "dev-master"
	}
}
```

2) Update your project using:

```
php composer.phar update
```

3) You need to extend your `AppKernel` with `BundleInheritanceKernel`. Your kernel need to look like:

```php
<?php
// app/AppKernel.php

use Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel as BaseKernel;

class AppKernel extends BaseKernel {
    // ...
}
```

4) Optional step: If you are using integrated symfony cacher, extend your `AppCache` with `Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\HttpCache\HttpCache`, like that:

```php
<?php
// app/AppCache.php

use Megogo\Bundle\MultipleInheritanceBundle\HttpKernel\HttpCache\HttpCache as BaseCache;

class AppCache extends BaseCache {
	// ...
}

```

5) Enable the bundle in the kernel, passing kernel itself to the bundle constructor:

```php
<?php
// app/AppKernel.php

public function registerBundles() {
    return array(
        // ...
        
        new Megogo\Bundle\MultipleInheritanceBundle\MultipleInheritanceBundle($this),
    );
}
```

6) Optional step: Normally, in child-bundles you need to re-create parts of the routing of parent bundle, with specifying child bundle name in the `_controller` part. If you plan to create child-bundles, which fully copy routing structure of parent bundles, but with some limitations (e.g. host restriction, like subdomain), you need to append this in your application's `routing.yml`:

```yml
# app/config/routing.yml

MultipleInheritanceBundle:
	resource: .
	type: inheritance
```
More about that you can read at [Full routing copying](full_routing_copying.md) page.


All done! Bundle is registered. Now you can start inheriting your bundles.



