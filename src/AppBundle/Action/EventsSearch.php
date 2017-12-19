<?php

namespace AppBundle\Action;

use AppBundle\Service\EventsSearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class EventsSearch
{
    private $router;
    private $searchService;

    /**
     * The action is automatically registered as a service and dependencies are autowired.
     * Typehint any service you need, it will be automatically injected.
     */
    public function __construct(RouterInterface $router, EventsSearchService $searchService)
    {
        $this->router = $router;
        $this->searchService = $searchService;
    }

    /**
     * @Route("/admin/events/search", name="admin_events_search")
     *
     * Using annotations is not mandatory, XML and YAML configuration files can be used instead.
     * If you want to decouple your actions from the framework, don't use annotations.
     */
    public function __invoke(Request $request)
    {
        $query = $request->get('q') ?? $request->get('query');
        $data = $this->searchService->search($query);

        return new JsonResponse(['results' => $data]);
    }
}
