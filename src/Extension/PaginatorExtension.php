<?php

namespace SilvainEu\Paginator\Extension;

use SilvainEu\Paginator\Factory\PaginatorFactory;
use SilvainEu\Paginator\Model\PageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginatorExtension extends AbstractExtension
{
    private const DEFAULT_PAGE_RANGE = 3;

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function getFunctions(): array
    {
        $options = ['is_safe' => ['html'], 'needs_environment' => true];
        return [
            new TwigFunction('paginator', [$this, 'generatePaginator'], $options),
        ];
    }

    /**
     * Generate paginator HTML code
     * @param Environment $twig Twig environment
     * @param PageInterface $page Page to generate paginator for
     * @return string Paginator HTML code
     */
    public function generatePaginator(Environment $twig, PageInterface $page): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return '';
        }
        $route = $request->attributes->get('_route');
        $pagesInRange = \range(1, $page->getNbPages());

        $proximity = \floor(self::DEFAULT_PAGE_RANGE / 2);

        $startPage = max($page->getCurrentPage() - $proximity, 1);
        $endPage = min($page->getNbPages() + $proximity, $page->getNbPages());

        try {
            return $twig->render('@SilvainEuPaginator/paginator.html.twig', [
                'page' => $page,
                'pageCount' => $page->getNbPages(),
                'current' => $page->getCurrentPage(),
                'next' => $page->hasNextPage() === false ? false : $page->getCurrentPage() + 1,
                'previous' => $page->hasPreviousPage() === false ? false : $page->getCurrentPage() - 1,
                'startPage' => $startPage,
                'endPage' => $endPage,
                'route' => $route,
                'query' => $request->query->all(),
                'pageParameterName' => PaginatorFactory::QUERY_PARAM_PAGE,
                'pagesInRange' => $pagesInRange,
            ]);
        } catch (LoaderError|RuntimeError|SyntaxError) {
            return '';
        }
    }
}
