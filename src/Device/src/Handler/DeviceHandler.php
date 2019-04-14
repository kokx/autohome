<?php

namespace Device\Handler;

use Device\Service\DeviceService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class DeviceHandler implements RequestHandlerInterface
{

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * @var DeviceService
     */
    protected $deviceService;

    /**
     * DeviceHandler constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param DeviceService $deviceService
     */
    public function __construct(TemplateRendererInterface $templateRenderer, DeviceService $deviceService)
    {
        $this->templateRenderer = $templateRenderer;
        $this->deviceService = $deviceService;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $deviceName = $request->getAttribute('name');
        $device = $this->deviceService->getDevice($deviceName);
        $sensorData = $this->deviceService->getLastSensorData($deviceName);

        return new HtmlResponse(
            $this->templateRenderer->render('device::device', [
                'device' => $device,
                'sensorData' => $sensorData
            ])
        );
    }
}
