<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Tests;


use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child1Bundle\Child1Bundle;
use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child2Bundle\Child2Bundle;
use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\ParentBundle\ParentBundle;
use Igorynia\Bundle\MultipleInheritanceBundle\Tests\Fixtures\TestKernel;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BundleInterface
     */
    protected $parentBundle;
    /**
     * @var BundleInterface
     */
    protected $child1Bundle;
    /**
     * @var BundleInterface
     */
    protected $child2Bundle;

    protected function setUp()
    {
        $this->parentBundle = new ParentBundle();
        $this->child1Bundle = new Child1Bundle();
        $this->child2Bundle = new Child2Bundle();
    }

    protected function buildKernel()
    {
        $kernel = new TestKernel('TEST', true, array(
            $this->parentBundle,
            $this->child1Bundle,
            $this->child2Bundle
        ));
        $kernel->boot();

        return $kernel;
    }

    protected function getBundleToStringConverter()
    {
        return function (BundleInterface $bundle) {
            return $bundle->getName();
        };
    }

}