<?php
namespace Mosaika\RadBundle\Event;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\Event;
use Mosaika\RadBundle\Core\Generator\RadGenerator;

class BuildEvent extends Event{
    /**
     * 
     * @var \Mosaika\RadBundle\Command\BuildCommand
     */
    protected $command;
    
    /**
     * 
     * @var RadGenerator
     */
    protected $generator;

    /**
     *
     * @var InputInterface
     */
    protected $input;

    /**
     *
     * @var OutputInterface
     */
    protected $output;
    

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

 /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

 /**
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

 /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    public function getGenerator()
    {
        return $this->generator;
    }

    public function setGenerator(RadGenerator $generator)
    {
        $this->generator = $generator;
        return $this;
    }

    public function getCommand() {
        return $this->command;
    }

    public function setCommand($command) {
        $this->command = $command;
        return $this;
    }
 
 
    
    
    
}

?>