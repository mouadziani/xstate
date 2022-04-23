<?php

namespace Mouadziani\XState;

use Closure;
use Mouadziani\XState\Exceptions\TransitionNotAllowedException;

class StateMachine
{
    private array $states = [];

    /** @var Transition[] */
    private array $transitions;

    private ?string $defaultState = null;

    private ?string $currentState = null;

    private ?Closure $beforeEachTransition = null;

    private ?Closure $afterTransition = null;

    public static function make(): self
    {
        return new static();
    }

    public function defaultState(string $default): self
    {
        $this->defaultState = $default;
        $this->currentState = $default;

        return $this;
    }

    public function states(array $states): self
    {
        $this->states = $states;

        return $this;
    }

    public function transitions(array $transitions): self
    {
        $this->transitions = $transitions;

        return $this;
    }

    public function beforeEachTransition(Closure $beforeEachTransition): self
    {
        $this->beforeEachTransition = $beforeEachTransition;

        return $this;
    }

    public function afterEachTransition(Closure $afterTransition): self
    {
        $this->afterTransition = $afterTransition;

        return $this;
    }

    public function currentState(): string
    {
        return $this->currentState;
    }

//    public function transitionTo(string $trigger): self
//    {
//        if (! $this->canBe($state)) {
//            throw new TransitionNotAllowedException('Transition not allowed');
//        }
//
//        call_user_func($this->beforeEachTransition, $this->currentState, $state);
//
//        $this->currentState = $state;
//
//        call_user_func($this->afterTransition, $this->currentState, $state);
//
//        return $this;
//    }

    public function canBe(string $state): bool
    {
        return in_array($state, $this->states[$this->currentState] ?? []);
    }
}
