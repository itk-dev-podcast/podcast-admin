<?php

namespace AppBundle\Action;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Frontpage
{
    private $router;
    private $twig;

    /**
     * The action is automatically registered as a service and dependencies are autowired.
     * Typehint any service you need, it will be automatically injected.
     */
    public function __construct(RouterInterface $router, \Twig_Environment $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="frontpage")
     *
     * Using annotations is not mandatory, XML and YAML configuration files can be used instead.
     * If you want to decouple your actions from the framework, don't use annotations.
     */
    public function __invoke(Request $request)
    {
        if (!$request->isMethod('GET')) {
            // Redirect to the current URL using the the GET method if it's not the current one
            return new RedirectResponse($this->router->generateUrl('frontpage'), 301);
        }

        return new Response($this->twig->render('frontpage.html.twig'));
    }
}
