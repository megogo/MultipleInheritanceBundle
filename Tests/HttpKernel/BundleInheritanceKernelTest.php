<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests\HttpKernel;


use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\TestKernel;
use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\UnknownParentBundle\UnknownParentBundle;
use Igorynia\Bundle\MultipleInheritanceBundle\Tests\TestCase;

class BundleInheritanceKernelTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionWhenGettingFirstUnregisteredBundle()
    {
        $kernel = $this->buildKernel();

        $kernel->getBundle('SomeUnregisteredBundle', true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionWhenGettingAllBundlesFromUnregisteredBundle()
    {
        $kernel = $this->buildKernel();

        $kernel->getBundle('SomeUnregisteredBundle', false);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadingBundleWithUnknownParent() {
        $kernel = new TestKernel('TEST', true, array(
            new UnknownParentBundle(),
        ));

        $kernel->boot();
    }

    public function testGettingFirstBundleWithoutInjectingActiveBundle()
    {
        $kernel = $this->buildKernel();
        $kernel->setActiveBundle(null);

        $this->assertEquals(
            $this->parentBundle->getName(),
            $kernel->getBundle($this->parentBundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->child1Bundle->getName(),
            $kernel->getBundle($this->child1Bundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->child2Bundle->getName(),
            $kernel->getBundle($this->child2Bundle->getName(), true)->getName()
        );
    }

    public function testGettingFirstBundleWithInjectingActiveBundle()
    {
        $kernel = $this->buildKernel();

        // Injecting parent bundle
        $kernel->setActiveBundle($this->parentBundle);
        $this->assertEquals(
            $this->child1Bundle->getName(),
            $kernel->getBundle($this->child1Bundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->child2Bundle->getName(),
            $kernel->getBundle($this->child2Bundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->parentBundle->getName(),
            $kernel->getBundle($this->parentBundle->getName(), true)->getName()
        );

        // Injecting child bundle
        $kernel->setActiveBundle($this->child1Bundle);
        $this->assertEquals(
            $this->child1Bundle->getName(),
            $kernel->getBundle($this->child1Bundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->child2Bundle->getName(),
            $kernel->getBundle($this->child2Bundle->getName(), true)->getName()
        );
        $this->assertEquals(
            $this->child1Bundle->getName(),
            $kernel->getBundle($this->parentBundle->getName(), true)->getName(),
            'ChildBundle must be returned, when ChildBundle is active and ParentBundle is called'
        );
    }

    public function testGettingAllBundlesWithoutInjectingActiveBundle()
    {
        $converter = $this->getBundleToStringConverter();
        $kernel    = $this->buildKernel();
        $kernel->setActiveBundle(null);

        $this->assertEquals(
            array_map($converter, array($this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->parentBundle->getName(), false)),
            'When getting ParentBundle, only it must be returned'
        );

        $this->assertEquals(
            array_map($converter, array($this->child1Bundle, $this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->child1Bundle->getName(), false)),
            'When getting Child1Bundle, must be returned array with two bundles, child1 and parent'
        );

        $this->assertEquals(
            array_map($converter, array($this->child2Bundle, $this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->child2Bundle->getName(), false)),
            'When getting Child2Bundle, must be returned array with two bundles, child2 and parent'
        );
    }

    public function testGettingAllBundlesWithInjectingActiveBundle()
    {
        $converter = $this->getBundleToStringConverter();
        $kernel    = $this->buildKernel();
        $kernel->setActiveBundle($this->child1Bundle);

        $this->assertEquals(
            array_map($converter, array($this->child1Bundle, $this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->parentBundle->getName(), false))
        );

        $this->assertEquals(
            array_map($converter, array($this->child1Bundle, $this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->child1Bundle->getName(), false))
        );

        $this->assertEquals(
            array_map($converter, array($this->child2Bundle, $this->parentBundle)),
            array_map($converter, $kernel->getBundle($this->child2Bundle->getName(), false))
        );
    }

}