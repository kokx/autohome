<?php

namespace Device\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Device\Service\GeneralDeviceService;
use Zend\Expressive\Template\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\HtmlResponse;

class SensorHandler implements RequestHandlerInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * SensorHandler constructor.
     */
    public function __construct(GeneralDeviceService $generalDeviceService, TemplateRendererInterface $templateRenderer)
    {
        $this->generalDeviceService = $generalDeviceService;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $device = $this->generalDeviceService->getDevice($request->getAttribute('device'));
        $sensor = $request->getAttribute('sensor');

        return new HtmlResponse(
            $this->templateRenderer->render('device::sensor', [
                'device' => $device,
                'sensor' => $sensor
            ])
        );
    }
}
