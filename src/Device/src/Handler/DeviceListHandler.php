<?php

namespace Device\Handler;

use Device\Service\GeneralDeviceService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class DeviceListHandler implements RequestHandlerInterface
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
     * DeviceListHandler constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param GeneralDeviceService $generalDeviceService
     */
    public function __construct(TemplateRendererInterface $templateRenderer, GeneralDeviceService $generalDeviceService)
    {
        $this->templateRenderer = $templateRenderer;
        $this->generalDeviceService = $generalDeviceService;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $devices = $this->generalDeviceService->getAllDevices();
        return new HtmlResponse(
            $this->templateRenderer->render('device::device-list', [
                'devices' => $devices
            ])
        );
    }
}
