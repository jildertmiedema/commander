<?php
namespace JildertMiedema\Commander\Silex;

use Silex\Application;
use JildertMiedema\Commander\CommandBus;

class DefaultCommandBus implements CommandBus {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CommandTranslator
     */
    protected $commandTranslator;

    /**
     * List of optional decorators for command bus.
     *
     * @var array
     */
    protected $decorators = [];

    /**
     * @param Application $app
     * @param CommandTranslator $commandTranslator
     */
    function __construct(Application $app, CommandTranslator $commandTranslator)
    {
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;
    }

    /**
     * Execute the command
     *
     * @param $command
     * @return mixed
     */
    public function execute($command)
    {
        $this->executeDecorators($command);

        $handler = $this->commandTranslator->toCommandHandler($command);

        return $this->app[$handler]->handle($command);
    }

    /**
     * Decorate the command bus with any executable actions.
     *
     * @param  string $className
     * @return mixed
     */
    public function decorate($className)
    {
        $this->decorators[] = $className;
    }

    /**
     * Execute all registered decorators
     *
     * @param  object $command
     * @return null
     */
    protected function executeDecorators($command)
    {
        foreach ($this->decorators as $className)
        {
            $instance = $this->app[$className];

            if ( ! $instance instanceof CommandBus)
            {
                $message = 'The class to decorate must be an implementation of JildertMiedema\Commander\CommandBus';

                throw new InvalidArgumentException($message);
            }

            $instance->execute($command);
        }
    }
}