<?php
namespace Mosaika\RadBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Mosaika\RadBundle\Core\Generator\RadGenerator;
use Mosaika\RadBundle\Event\BuildEvent;

class BuildCommand extends ContainerAwareCommand
{

    protected $types = array(
        "entity"
    );

    protected function configure()
    {
        $this->setName('rad:build')
            ->setDescription('Build')
            ->addOption('schema-update', null, InputOption::VALUE_NONE, 'If set, doctrine schema update will be executed')
            ->addOption('load-fixtures', null, InputOption::VALUE_NONE, 'If set, doctrine fixtures load will be executed')
            ->addOption('append', null, InputOption::VALUE_NONE, 'If set, doctrine fixtures will be executed in append mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        /*
         * $scopes = $this->types;
         * $what = $input->getArgument('what');
         * if($what){
         * $scopes = array($what);
         * }
         */
        $dispatcher = $this->getContainer()->get("event_dispatcher");
        $generator = new RadGenerator($this->getContainer());
        $event = new BuildEvent();
        $event->setInput($input);
        $event->setOutput($output);
        $event->setGenerator($generator);
        $event->setCommand($this);
        $dispatcher->dispatch("rad.build", $event);
        $dispatcher->dispatch("rad.build.commit", $event);
        $generator->commit();
        
        $kernel = $this->getContainer()->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        if ($input->getOption("schema-update")) {
            // Create de Schema
            $options = array(
                'command' => 'doctrine:schema:update',
                "--force" => true
            );
            $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
        }
        if ($input->getOption("load-fixtures")) {
            // Loading Fixtures
            $options = array(
                'command' => 'doctrine:fixtures:load'
            );
            if ($input->getOption("append")) {
                $options["--append"] = true;
            } else {
                $options["--purge-with-truncate"] = true;
            }
            $options["-n"] = true;
            $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
        }
    }
}

?>