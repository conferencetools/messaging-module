<?php

namespace ConferenceTools\Messaging\Handler;

use Interop\Container\ContainerInterface;
use Phactor\Zend\RepositoryManager;
use Zend\Mail\Transport\Factory;
use Zend\ServiceManager\Factory\FactoryInterface;

class SendEmailFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $transport = Factory::create($config['mail']);

        return new SendEmail(
            $container->get(RepositoryManager::class),
            $container->get('Zend\View\View'),
            $transport,
            $config['conferencetools']['emails']
        );
    }
}
