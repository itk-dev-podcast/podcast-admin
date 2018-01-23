<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EntitySearch implements EntitySearchInterface
{
    private $container;
    private $requestStack;

    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    public function search($resourceClass, array $query)
    {
        $attributes = [
            '_api_resource_class' => $resourceClass,
            '_api_collection_operation_name' => 'get',
        ];
        $request = new Request($query, [], $attributes);
        $this->requestStack->push($request);
        $provider = $this->container->get('api_platform.collection_data_provider');
        $result = $provider->getCollection($resourceClass, 'get');
        $this->requestStack->pop();

        return $result;
        return $this->container->get('doctrine.orm.default_entity_manager')->getRepository($resourceClass)->findAll();
        $listener = $this->container->get('api_platform.listener.request.read');
        $kernel = $this->container->get('kernel');
        $request = new Request($query, [], $attributes);
        $event = new GetResponseEvent($kernel, $request, HttpKernelInterface::SUB_REQUEST);
        $listener->onKernelRequest($event);
        $result = $request->attributes->get('data');

        return $result;
    }
}
