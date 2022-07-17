<?php

namespace Mouadziani\XState;

use Mouadziani\XState\Exceptions\TransitionNotAllowedException;
use Mouadziani\XState\Exceptions\TransitionNotDefinedException;

class StateMachine
{
    public array $states;

    private array $transitions;

    public ?string $defaultState;

    private ?string $currentState = null;

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

    public function addState(string $state): self
    {
        $this->states[] = $state;

        return $this;
    }

    public function transitions(array $transitions): self
    {
        $this->transitions = $transitions;

        return $this;
    }

    public function addTransition(Transition $transition): self
    {
        $this->transitions[] = $transition;

        return $this;
    }

    public function currentState(): string
    {
        return $this->currentState;
    }

    public function transitionTo(string $trigger): self
    {
        $transition = $this->findTransition($trigger);

        if (! $transition) {
            throw new TransitionNotDefinedException('Transition not defined');
        }

        if (! in_array($trigger, $this->allowedTransitions())) {
            throw new TransitionNotAllowedException('Transition not allowed');
        }

        $transition->handle($this->currentState);

        return $this;
    }

    public function canTransisteTo(string $trigger): bool
    {
        $transition = $this->findTransition($trigger);

        return $transition && $transition->allowed() && in_array($trigger, $this->allowedTransitions());
    }

    public function allowedTransitions(): array
    {
        $allowedTransitions = array_filter($this->transitions, function ($transition) {
            return in_array($this->currentState(), is_array($transition->from) ? $transition->from : [$transition->from]);
        });

        return array_map(fn ($transition) => $transition->trigger, array_values($allowedTransitions));
    }

    public function __call(string $name, array $arguments)
    {
        $this->transitionTo(strtoupper($name));
    }

    private function findTransition(string $trigger): ?Transition
    {
        return array_values(
            array_filter($this->transitions, fn ($transition) => $transition->trigger === $trigger) ?? []
        )[0] ?? null;
    }
}
