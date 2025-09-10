<?php

declare(strict_types=1);

namespace App\Command;

use App\Fixture\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Yiisoft\Yii\Console\ExitCode;


final class FixtureCommand extends Command
{
    protected static $defaultName = 'fixture:load';

    private FixtureLoader $fixtureLoader;

    public function __construct(FixtureLoader $fixtureLoader)
    {
        $this->fixtureLoader = $fixtureLoader;
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setDescription('Load fixtures')
            ->setHelp('This command load necessary fixed data');
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->fixtureLoader->load();

        return ExitCode::OK;
    }
}
