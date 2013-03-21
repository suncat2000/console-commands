Console commands tool in PHP
================

Tool to quickly write console commands in PHP.
It is based on a [Symfony Console Component](http://symfony.com/doc/master/components/console/introduction.html).

Depends:
* [symfony/console](https://github.com/symfony/Console)
* [symfony/finder](https://github.com/symfony/Finder)
* [suncat/symfony-console-extra](https://github.com/suncat2000/symfony-console-extra)

Installation
================

Run command:
````
$ composer create-project -s dev suncat/console-commands ./cmd
````
or
````
$ composer create-project suncat/console-commands ./cmd 1.0.x-dev
````

go to the `cmd` directory with project:

````
$ cd ./cmd
````
Done!

Usage
===============

Look at list available commands
````
$ app/console list
````

````
...
Available commands:
    generate   Generate skeleton class for new command
    help       Displays help for a command
    list       Lists commands
````

Generate skeleton command class:
````
$ app/console generate
````
Write name of your command class in console dialog:

`Please enter the name of the command class:` AcmeCommand

Get the answer:
````
Generated new command class to "./cmd/src/Command/AcmeCommand.php"
````
Look at list available commands
````
$ app/console list
````
````
...
Available commands:
    acme       Command acme
    generate   Generate skeleton class for new command
    help       Displays help for a command
    list       Lists commands
````

Execute your command `acme`:

````
$ app/console acme

Execute

````
Now you can change the logic of your command class on your own.

If the name of your command class will be in CamelCase you get `camel:case` command.