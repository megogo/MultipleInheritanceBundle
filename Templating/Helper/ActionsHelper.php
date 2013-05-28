<?php

namespace Igorynia\Bundle\MultipleInheritanceBundle\Templating\Helper;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Bundle\FrameworkBundle\Templating\Helper\ActionsHelper as BaseHelper;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

/**
 * Class ActionsHelper overrides default ControllerReference creation logic to change short controller names
 * in sync with active bundle.
 *
 * @package Igorynia\Bundle\MultipleInheritanceBundle\Templating\Helper
 */
class ActionsHelper extends BaseHelper
{

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser
     */
    private $parser;

    public function __construct(FragmentHandler $handler, ControllerNameParser $parser)
    {
        parent::__construct($handler);

        $this->parser = $parser;
    }

    public function controller($controller, $attributes = array(), $query = array())
    {
        if (false === strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                $controller = $this->parser->parse($controller);
            }
        }

        return parent::controller($controller, $attributes, $query);
    }

}