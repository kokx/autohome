<?php
declare(strict_types=1);

namespace Device\Handler;

use Device\Service\GeneralDeviceService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class SetActuatorHandler implements RequestHandlerInterface
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
     * SetActuatorHandler constructor.
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
        $deviceIdentifier = $request->getAttribute('device');
        $device = $this->generalDeviceService->getDevice($deviceIdentifier);
        $deviceService = $this->generalDeviceService->getDeviceService($device);

        $actuator = $request->getAttribute('actuator');

        $deviceService->setActuator($device, $actuator, $request->getParsedBody());

        return new HtmlResponse($this->templateRenderer->render("device::actuator", [
            'device' => $device,
            'showActuator' => $deviceService->showActuator($device, $actuator)
        ]));
    }
}
