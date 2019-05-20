<?php

namespace X1a\Xml\FSM;

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Statemachine;
use Metabor\Statemachine\Transition;
use Metabor\Observer;
use Metabor\Callback;

class Parser
{
    const STATUS_INITIAL = 'initial';

    /**
     * @var array
     */
    protected $states = [];

    /**
     * @var Statemachine
     */
    protected $fsm;

    /**
     * Parser constructor.
     * @param string[] $states list of all possible states
     * @param array[] $transitions list of all possible transitions
     *                              each transition is defined as an array like
     *                              [<initial_state>, <final_state>, <event_name>]
     */
    public function __construct(array $states, array $transitions)
    {
        $initialState = $this->getInitialState();
        if ($initialState != $states[0]) {
            array_unshift($states, $initialState);
        }

        foreach ($states as $state) {
            $this->states[$state] = new State($state);
        }

        foreach ($transitions as $transition) {
            $this->addTransition($transition[0], $transition[1], $transition[2]);
        }
    }

    /**
     * @return string
     */
    public function getInitialState(): string
    {
        return static::STATUS_INITIAL;
    }

    /**
     * @param string $name
     * @return State
     */
    public function getState(string $name): ?State
    {
        return isset($this->states[$name]) ? $this->states[$name] : null;
    }

    /**
     * @param string $fromState
     * @param string $toState
     * @param string $event
     * @return Parser
     */
    public function addTransition(string $fromState, string $toState, string $event): Parser
    {
        $this->getState($fromState)->addTransition(new Transition($this->getState($toState), $event));
        return $this;
    }

    /**
     * @return Statemachine
     */
    public function getStatemachine(): Statemachine
    {
        if (null === $this->fsm) {
            $subject = new \stdClass();
            $process = new Process('XML Parser', $this->getState($this->getInitialState()));
            $this->fsm = new Statemachine($subject, $process);
        }
        return $this->fsm;
    }

    /**
     * @param string $name
     * @param \ArrayAccess|null $context
     * @return $this
     */
    public function triggerEvent(string $name, \ArrayAccess $context = null): Parser
    {
        $this->getStatemachine()->triggerEvent($name, $context);
        return $this;
    }

    /**
     *
     * @param string|string[] $state
     * @param string $event
     * @param callable $handler
     * @return $this
     */
    public function attachHandler($state, string $event, callable $handler): Parser
    {
        if (is_string($state)) {
            $state = [$state];
        }

        foreach ($state as $s) {
            $observer = new Observer\Callback(new Callback\Callback($handler));
            $this->getState($s)->getEvent($event)->attach($observer);
        }

        return $this;
    }
}