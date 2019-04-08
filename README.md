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
Thus, as a database, sqlite is used. And the message queue is implemented as a
table in sqlite. This helps in quick setup.
