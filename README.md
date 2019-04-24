# Autohome
Autohome is a project for user-friendly home automation. Currently heavily Work
in Progress.

Mostly written because of a dissatisfaction with the Domoticz interface. The
goal is to be a home automation system for computer scientists, who don't shy
away from modifying config files and the command line. And possibly even write
plugins. But to have an easy-to-use interface for their (non-technical)
co-habitants. Thus, providing a very simple web interface to manipulate the home
automation system itself, but not a point-and-click interface for configuring
devices.


# Run it for development

Copy `config/autoload/devices.local.php.dist` to
`config/autoload/devices.local.php` and adjust it to your own setup.

Run `npm install --dev` and `composer install` to install all dependencies

Run `composer run --timeout=0` and `npm start` to start a server on http://localhost:8000

# Device support

Currently, autohome has support for an OpenTherm gateway
(http://otgw.tclcode.com) connected to a device running a Ser2Net (Serial to
TCP) server. Recommended is to use a NodeMCU with ESPEasy firmware.

Autohome also supports computers running Linux, which can be turned on using
Wake-On-Lan and turned off by sending `sudo systemctl poweroff` over SSH.

# Architecture
This project is divided into two running parts, the webinterface and the
background service. The webinterface sends messages (via a message queue) to the
background service to change a light or the setpoint of the heater. The
background service then picks this up, and executes the command. Changing the
heating schedule is a more extreme but still fitting example of a message that
is sent to the background service.

The background service writes all the data it collects from the sensors to the
database. The webinterface reads the database and displays sensor data.

This system is targetted at simple home usage. Not enterprise environments.
Thus, as a database, sqlite is used. The message queue is implemented as a table
in sqlite. This makes everything quick to setup. Performance of sqlite is fast
enough that this will work good enough for us.

## Dependency Decisions

### Zend Expressive
Zend Expressive is a clean way of working with middleware. We could have gone
for Silex, Laravel, Slim, or any other middleware implementation. We chose this
one, because familiarity with it. Got any problems with it? Deal with it.

### Doctrine
This is probably not controversial at all. Doctrine is a great ORM.

### SlmQueue or roll our own
For the background service, we need some messaging system. Bernard looks great,
however it does not have features for delayed messaging or repeated execution.
Which is something we will need (for example, to check all sensors every 5
minutes). SlmQueue does have this feature in some of its supported queues
(notably the `SlmQueueDoctrine` queue).

However, it may be beneficial to write our own system. Especially since there
will be some jobs which would have to be executed in a regular interval. In
SlmQueue we would implement that by jobs adding themselves every time they run
and bootstrapping them somehow. This, however, seems prone to bugs. It might be
better to be able to configure some repeating jobs, which will be executed
regularly. Which would require us to roll our own.

Since rolling our own was quick and simple enough (and probably works good
enough as well, with some considerations), we will use this for now.
