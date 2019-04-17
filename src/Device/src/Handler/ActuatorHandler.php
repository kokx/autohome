<?php

namespace Device\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class ActuatorHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * ActuatorHandler constructor.
     */
    public function __construct(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->templateRenderer->render("device::actuator"));
    }
}