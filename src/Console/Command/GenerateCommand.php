<?php

namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    private $template = '<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* <class>
*/
class <class> extends Command
{
    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName("<name>")
            ->setDescription("Command <name>")
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array("","<info>Execute</info>",""));
    }
}';

    /**
     * Configuration of command
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate skeleton class for new command')
            ->setHelp(<<<EOF
The <info>generate</info> command create skeleton of new command class.

<info>php app/console generate</info>
EOF
            );
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            '',
            '<comment>Welcome to the command generator</comment>',
            ''
        ));
        /* @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');
        $commandClass = $dialog->askAndValidate(
            $output,
            "<info>Please enter the name of the command class</info>:",
            function ($answer) {
                if ('Command' !== substr($answer, -7)) {
                    throw new \RunTimeException(
                        'The name of the command should be suffixed with \'Command\''
                    );
                }
                return $answer;
            },
            false,
            'DefaultCommand'
        );

        //
        $commandName = $this->colonize($commandClass);

        $path = $this->generateCommand($commandClass, $commandName);
        $output->writeln(sprintf('Generated new command class to "<info>%s</info>"', realpath($path)));
    }

    /**
     * Generate command skeleton
     *
     * @param $commandClass
     * @param $commandName
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function generateCommand($commandClass, $commandName)
    {
        $placeHolders = array(
            '<class>',
            '<name>'
        );
        $replacements = array(
            $commandClass,
            $commandName
        );
        $code = str_replace($placeHolders, $replacements, $this->template);
        /* @var \Console\ApplicationExtra $app*/
        $app = $this->getApplication();
        $dir = $app->getCommandsDir();
        $dir = rtrim($dir, '/');
        $path = $dir . '/'.$commandClass.'.php';

        if (!file_exists($dir)) {
            throw new \Exception(sprintf('Commands directory "%s" does not exist.', $dir));
        }

        file_put_contents($path, $code);

        return $path;
    }

    /**
     * Colonize command name
     *
     * @param $word
     * @return string
     */
    protected function colonize($word)
    {
        $word = str_replace('Command', '', $word);

        return  strtolower(preg_replace('/[^A-Z^a-z^0-9]+/',':',
                preg_replace('/([a-zd])([A-Z])/','\1:\2',
                    preg_replace('/([A-Z]+)([A-Z][a-z])/','\1:\2',$word))));
    }
}