<?php

namespace Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

use Console\Command\GenerateCommand;

class ApplicationExtra extends Application
{

    private $logo = '   ______                       __          _______            __
  / ____/___  ____  ____  ___  / /  ___    /__  __/___   ___  / /
 / /   / __ \/ __ \/ ___/ __ \/ /  / _ \     / / / __ \/ __ \/ /
/ /___/ /_/ / / / (__ )/ /_/ / /__/  __/    / / / /_/ / /_/ / /__
\____/\____/_/ /_/____/\____/____/\___/    /_/  \____/\____/____/
';
    protected $commandsDir;

    /**
     * Constructor.
     *
     * @param string $commandsDir The commands class directory
     * @param string $name        The name of the application
     * @param string $version     The version of the application
     *
     * @api
     */
    public function __construct($commandsDir, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        if (!is_dir($commandsDir)) {
            throw new \Exception('First argument is not directory!');
        }

        parent::__construct($name, $version);
        $this->commandsDir = $commandsDir;
        // register commands
        $this->registerCommands();
    }

    /**
     * Register commands
     */
    public function registerCommands()
    {
        $finder = new Finder();
        $finder->files()->name('*Command.php')->in($this->commandsDir);

        $prefix = 'Command';
        foreach ($finder as $file) {
            $ns = $prefix;
            /* @var \Symfony\Component\Finder\SplFileInfo $file*/
            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }
            $r = new \ReflectionClass($ns.'\\'.$file->getBasename('.php'));
            if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract()) {
                $this->add($r->newInstance());
            }
        }
    }

    /**
     * Get commands class dir
     *
     * @return string
     */
    public function getCommandsDir()
    {
        return $this->commandsDir;
    }


    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     *
     * @api
     */
    public function getLongVersion()
    {
        if ('UNKNOWN' !== $this->getName() && 'UNKNOWN' !== $this->getVersion()) {
            return sprintf('<info>%s</info> version <comment>%s</comment>', $this->getName(), $this->getVersion());
        }

        return '<info>' . $this->logo . '</info>';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new GenerateCommand();

        return $defaultCommands;
    }
}