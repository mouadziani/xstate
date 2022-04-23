<?php

namespace Mouadziani\XState;

use Closure;
use Mouadziani\XState\Exceptions\TransitionNotAllowedException;

class StateMachine
{
    public array $states = [];

    public array $transitions = [];

    public mixed $defaultState = null;

    public mixed $currentState = null;

    public Closure $beforeTransition;

    public Closure $afterTransition;

    public static function make(): self
    {
        return new static;
    }

    public function defaultState(mixed $default)
    {
        $this->defaultState = $default;
        $this->currentState = $default;

        return $this;
    }

    public function states(array $states)
    {
        $this->states = $states;

        return $this;
    }

    public function transitions(array $transitions)
    {
        $this->transitions = $transitions;

        return $this;
    }

    public function beforeTransition(Closure $beforeTransition)
    {
        $this->beforeTransition = $beforeTransition;

        return $this;
    }

    public function afterTransition(Closure $afterTransition)
    {
        $this->afterTransition = $afterTransition;

        return $this;
    }

    public function currentState(): mixed
    {
        return $this->currentState;
    }

    public function transitionTo(mixed $state): self
    {
        if (! $this->canBe($state)) {
            throw new TransitionNotAllowedException('Transition not allowed');
        }

        call_user_func($this->beforeTransition, $this->currentState, $state);

        $this->currentState = $state;

        call_user_func($this->afterTransition, $this->currentState, $state);

        return $this;
    }

    public function canBe(mixed $state): bool
    {
        return in_array($state, $this->states[$this->currentState] ?? []);
    }
}
