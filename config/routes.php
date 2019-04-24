<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Device\Handler\SensorDataHandler;
use Device\Handler\SensorHandler;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');

    $app->get('/devices', \Device\Handler\DeviceListHandler::class, 'devices.list');
    $app->get('/devices/{name:[a-zA-Z0-9._-]+}', \Device\Handler\DeviceHandler::class, 'devices.show');
    $app->get('/devices/{device:[a-zA-Z0-9._-]+}/sensors', \Device\Handler\SensorsHandler::class, 'devices.sensors');
    $app->get('/devices/{device:[a-zA-Z0-9._-]+}/actuator/{actuator:[a-zA-Z0-9._-]+}', \Device\Handler\ActuatorHandler::class, 'devices.actuator');
    $app->post('/devices/{device:[a-zA-Z0-9._-]+}/actuator/{actuator:[a-zA-Z0-9._-]+}', \Device\Handler\SetActuatorHandler::class, 'devices.actuator.post');
    $app->get('/devices/{device:[a-zA-Z0-9._-]+}/sensors/{sensor:[a-zA-Z0-9._-]+}', SensorHandler::class, 'devices.sensor');

    $app->get('/api/devices/{device:[a-zA-Z0-9._-]+}/sensors/{sensor:[a-zA-Z0-9._-]+}/log', SensorDataHandler::class, 'api.devices.sensorlog');
};
