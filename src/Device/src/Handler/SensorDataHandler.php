<?php

namespace Device\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Device\Service\GeneralDeviceService;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Device\Entity\SensorLog;

class SensorDataHandler implements RequestHandlerInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * SensorDataHandler Constructor.
     */
    public function __construct(GeneralDeviceService $generalDeviceService)
    {
        $this->generalDeviceService = $generalDeviceService;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $device = $this->generalDeviceService->getDevice($request->getAttribute('device'));
        $sensor = $request->getAttribute('sensor');

        switch ($request->getAttribute('timespan')) {
            case 'year':
                $log = $this->generalDeviceService->getYearSensorStats($device, $sensor);
                break;
            case 'month':
                $log = $this->generalDeviceService->getMonthSensorStats($device, $sensor);
                break;
            case 'day':
            default:
                $log = $this->generalDeviceService->getDaySensorLog($device, $sensor);

                $log = array_map(function (SensorLog $item) {
                    return [
                        'state' => $item->getState(),
                        'created_at' => $item->getCreatedAt()->format('c'),
                    ];
                }, $log);
                break;
        }

        return new JsonResponse([
            'device' => [
                'identifier' => $device->getIdentifier(),
                'name' => $device->getName()
            ],
            'sensor' => $sensor,
            'log' => $log
        ]);
    }
}
