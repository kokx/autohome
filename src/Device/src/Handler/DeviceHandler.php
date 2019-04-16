<?php

namespace Device\Handler;

use Device\Service\GeneralDeviceService;
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
     * @var GeneralDeviceService
     */
    protected $deviceService;

    /**
     * DeviceHandler constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param GeneralDeviceService $deviceService
     */
    public function __construct(TemplateRendererInterface $templateRenderer, GeneralDeviceService $deviceService)
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
        $sensorData = $this->deviceService->getLastSensorData($device);

        $deviceService = $this->deviceService->getDeviceService($device);

        return new HtmlResponse(
            $this->templateRenderer->render('device::device', [
                'device' => $device,
                'sensorData' => $sensorData,
                'summary' => $deviceService->getSummary($device),
            ])
        );
    }
}
