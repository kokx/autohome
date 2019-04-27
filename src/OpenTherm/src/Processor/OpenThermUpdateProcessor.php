<?php

namespace OpenTherm\Processor;

use OpenTherm\Device\OpenThermGateway;
use Device\Service\GeneralDeviceService;
use Queue\Message\Message;
use Queue\Processor\ProcessorInterface;

class OpenThermUpdateProcessor implements ProcessorInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $deviceService;

    /**
     * OpenThermUpdateProcessor constructor.
     */
    public function __construct(GeneralDeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * Read sensor data from the OpenTherm Gateway.
     *
     * This sends the `PS=1` command to the OpenTherm Gateway (OTGW). The response consists of two lines. One line
     * with `PS: 1`, confirming our command. The second line contains the following data:
     *
     * - Status (MsgID=0) - Printed as two 8-bit bitfields
     * - Control setpoint (MsgID=1) - Printed as a floating point value
     * - Remote parameter flags (MsgID= 6) - Printed as two 8-bit bitfields
     * - Maximum relative modulation level (MsgID=14) - Printed as a floating point value
     * - Boiler capacity and modulation limits (MsgID=15) - Printed as two bytes
     * - Room Setpoint (MsgID=16) - Printed as a floating point value
     * - Relative modulation level (MsgID=17) - Printed as a floating point value
     * - CH water pressure (MsgID=18) - Printed as a floating point value
     * - Room temperature (MsgID=24) - Printed as a floating point value
     * - Boiler water temperature (MsgID=25) - Printed as a floating point value
     * - DHW temperature (MsgID=26) - Printed as a floating point value
     * - Outside temperature (MsgID=27) - Printed as a floating point value
     * - Return water temperature (MsgID=28) - Printed as a floating point value
     * - DHW setpoint boundaries (MsgID=48) - Printed as two bytes
     * - Max CH setpoint boundaries (MsgID=49) - Printed as two bytes
     * - DHW setpoint (MsgID=56) - Printed as a floating point value
     * - Max CH water setpoint (MsgID=57) - Printed as a floating point value
     * - Burner starts (MsgID=116) - Printed as a decimal value
     * - CH pump starts (MsgID=117) - Printed as a decimal value
     * - DHW pump/valve starts (MsgID=118) - Printed as a decimal value
     * - DHW burner starts (MsgID=119) - Printed as a decimal value
     * - Burner operation hours (MsgID=120) - Printed as a decimal value
     * - CH pump operation hours (MsgID=121) - Printed as a decimal value
     * - DHW pump/valve operation hours (MsgID=122) - Printed as a decimal value
     * - DHW burner operation hours (MsgID=123) - Printed as a decimal value
     *
     * (source: http://otgw.tclcode.com/firmware.html#configuration)
     *
     * Example of the second line:
     *
     * > 00000010/00000000,10.00,00000011/00000000,100.00,0/0,16.00,0.00,0.00,20.32,52.00,0.00,0.00,0.00,0/0,0/0,0.00,80.00,51166,0,0,0,2102,0,0,539
     */
    public function process(Message $message): void
    {
        $payload = $message->getPayload();

        if (!isset($payload['device'])) {
            throw new \RuntimeException("No device given.");
        }

        /** @var OpenThermGateway $device */
        $device = $this->deviceService->getDevice($payload['device']);

        $socket = fsockopen($device->getHost(), $device->getPort());

        // CR-LF is required. The gateway won't respond otherwise
        fwrite($socket, "PS=1\r\n");

        $newlines = 0;
        $data = "";

        while (!feof($socket) && $newlines < 2) {
            $out = fread($socket, 4096);
            $newlines += substr_count($out, "\n");
            $data .= $out;
        }

        $data = trim(str_replace("\r\n", "\n", $data));
        $data = explode("\n", $data);

        if (count($data) < 2) {
            throw new \RuntimeException("Not enough data in OpenTherm Gateway output.");
        }

        $data = explode(',', $data[1]);

        if (count($data) < 25) {
            throw new \RuntimeException("Not enough data in OpenTherm Gateway output.");
        }

        $data = [
            'status'                        => $data[0],
            'control_setpoint'              => $data[1],
            'remote_flags'                  => $data[2],
            'max_relative_modulation'       => $data[3],
            'boiler_cap_mod_limits'         => $data[4],
            'room_setpoint'                 => $data[5],
            'relative_modulation'           => $data[6],
            'ch_water_pressure'             => $data[7],
            'room_temperature'              => $data[8],
            'boiler_water_temperature'      => $data[9],
            'dhw_temperature'               => $data[10],
            'outside_temperature'           => $data[11],
            'return_water_temperature'      => $data[12],
            'dhw_setpoint_bounds'           => $data[13],
            'max_ch_setpoint_bounds'        => $data[14],
            'dhw_setpoint'                  => $data[15],
            'max_ch_water_setpoint'         => $data[16],
            'burner_starts'                 => $data[17],
            'ch_pump_starts'                => $data[18],
            'dhw_pump_starts'               => $data[19],
            'dhw_burner_starts'             => $data[20],
            'burner_operation_hours'        => $data[21],
            'ch_pump_operation_hours'       => $data[22],
            'dhw_pump_operation_hours'      => $data[23],
            'dhw_burner_operation_hours'    => $data[24],
        ];

        $this->deviceService->logSensorData($device, $data);

        fclose($socket);
    }
}
