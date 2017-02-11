<?php

namespace ModuleGenerator\ModuleGenerator\Settings\Console;

use ModuleGenerator\ModuleGenerator\Settings\Settings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SetDefaultPhpVersion extends Command
{
    /** @var Settings The service to interact with the settings */
    private $settings;

    /**
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('settings:set:default-php-version')
            ->setDescription('Sets the default php version')
            ->addArgument(
                'php-version',
                InputArgument::REQUIRED,
                'Possible values are: ' . implode(', ', $this->settings->get(Settings::SUPPORTED_PHP_VERSIONS))
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $requestedPhpVersion = $input->getArgument('php-version');
        if (!in_array($requestedPhpVersion, $this->settings->get(Settings::SUPPORTED_PHP_VERSIONS))) {
            throw new InvalidArgumentException(
                'PHP versions needs to be one of the allowed versions: ' . implode(
                    ', ',
                    $this->settings->get(Settings::SUPPORTED_PHP_VERSIONS)
                )
            );
        }

        $this->settings->set(Settings::DEFAULT_PHP_VERSION, $requestedPhpVersion);
    }
}
