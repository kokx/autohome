<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class HomePageHandler implements RequestHandlerInterface
{

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    public function __construct(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new HtmlResponse($this->templateRenderer->render('app::home-page'));
    }
}
