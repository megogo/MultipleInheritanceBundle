<?php


namespace Igorynia\Bundle\MultipleInheritanceBundle\EventListener;


use Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ActiveBundleDeterminationListener implements EventSubscriberInterface
{

    const ACTIVE_BUNDLE_ATTRIBUTE = '_active_bundle';

    /**
     * @var \Igorynia\Bundle\MultipleInheritanceBundle\HttpKernel\BundleInheritanceKernel
     */
    private $kernel;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(BundleInheritanceKernel $kernel, LoggerInterface $logger = null)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onControllerEvent'
        );
    }

    public function onControllerEvent(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()
            || null !== $this->kernel->getActiveBundle()
        ) {
            return;
        }

        if ($event->getRequest()->attributes->has(self::ACTIVE_BUNDLE_ATTRIBUTE)) {
            $bundle = $this->kernel->getBundle($event->getRequest()->attributes->get(self::ACTIVE_BUNDLE_ATTRIBUTE));
        } else {
            $controller      = $event->getController();
            $controllerClass = get_class($controller[0]);

            $bundle = $this->getBundleForClass($controllerClass);
        }

        if (null !== $this->logger && null !== $bundle) {
            $this->logger->debug(sprintf('Injecting active bundle "%s"', $bundle->getName()));
        }

        $this->kernel->setActiveBundle($bundle);
    }

    /**
     * @param $class
     *
     * @return BundleInterface|null
     */
    private function getBundleForClass($class)
    {
        foreach ($this->kernel->getBundles() as $bundle) {
            if (0 === strpos($class, $bundle->getNamespace())) {
                return $bundle;
            }
        }

        return null;
    }

}
