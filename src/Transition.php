<?php

namespace Mouadziani\XState;

use Closure;
use Mouadziani\XState\Exceptions\TransitionNotAllowedException;

class Transition
{
    public string $trigger;

    public string|array $from;

    public string $to;

    private ?Closure $guard = null;

    private ?Closure $before = null;

    private ?Closure $after = null;

    public function __construct(string $trigger, string|array $from, string $to)
    {
        $this->trigger = $trigger;
        $this->from = $from;
        $this->to = $to;
    }

    public function guard(Closure $guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    public function before(Closure $before): self
    {
        $this->before = $before;

        return $this;
    }

    public function after(Closure $before): self
    {
        $this->before = $before;

        return $this;
    }

    public function allowed(): bool
    {
        return ! $this->guard || call_user_func($this->guard, $this->from, $this->to);
    }

    public function handle(string &$currentState): void
    {
        if (! $this->allowed()) {
            throw new TransitionNotAllowedException();
        }

        $this->before && call_user_func($this->before, $this->from, $this->to);

        $currentState = $this->to;

        $this->after && call_user_func($this->after, $this->from, $this->to);
    }
}
