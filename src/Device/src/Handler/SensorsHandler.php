<?php

namespace Device\Handler;

use Device\Service\GeneralDeviceService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class SensorsHandler implements RequestHandlerInterface
{

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * SensorHandler constructor.
     */
    public function __construct(TemplateRendererInterface $templateRenderer, GeneralDeviceService $generalDeviceService)
    {
        $this->generalDeviceService = $generalDeviceService;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $deviceName = $request->getAttribute('device');
        $device = $this->generalDeviceService->getDevice($deviceName);
        $sensorData = $this->generalDeviceService->getLastSensorData($device);

        return new HtmlResponse(
            $this->templateRenderer->render('device::sensors', [
                'device' => $device,
                'sensorData' => $sensorData
            ])
        );
    }
}
