<?php

namespace Megogo\Bundle\MultipleInheritanceBundle\Tests\Templating;


use Megogo\Bundle\MultipleInheritanceBundle\Templating\Helper\ActionsHelper;
use Megogo\Bundle\MultipleInheritanceBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

class ActionsHelperTest extends TestCase
{

    public function testInheritedControllerNotChanged()
    {
        $kernel = $this->buildKernel();
        $kernel->setActiveBundle(null);

        $actionsHelper = $this->initActionsHelper($kernel);

        $this->assertEquals(
            'Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\ParentBundle\Controller\AwesomeController::indexAction',
            $actionsHelper->controller('ParentBundle:Awesome:index')->controller
        );
    }

    public function testInheritedControllerChangedToInheritedBundle()
    {
        $kernel = $this->buildKernel();
        $kernel->setActiveBundle($this->child1Bundle);

        $actionsHelper = $this->initActionsHelper($kernel);

        $this->assertEquals(
            'Megogo\Bundle\MultipleInheritanceBundle\Tests\Fixtures\Child1Bundle\Controller\AwesomeController::indexAction',
            $actionsHelper->controller('ParentBundle:Awesome:index')->controller
        );
    }

    protected function initActionsHelper($kernel)
    {
        $controllerNameParser = new ControllerNameParser($kernel);
        $fragmentHandler      = new FragmentHandler(array(), true);
        $actionsHelper        = new ActionsHelper($fragmentHandler, $controllerNameParser);

        return $actionsHelper;
    }

}