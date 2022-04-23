<?php

namespace Mouadziani\XState;

use Closure;

class Transition
{
    public string $trigger;
    public string|array $from;
    public string $to;
    public ?Closure $beforeHook;
    public ?Closure $afterHook;

    public function __construct(string $trigger, string|array $from, string $to, ?Closure $beforeHook = null, ?Closure $afterHook = null)
    {
        $this->trigger = $trigger;
        $this->from = $from;
        $this->to = $to;
        $this->beforeHook = $beforeHook;
        $this->afterHook = $afterHook;
    }

    public function handle(string &$currentState, ?Closure $beforeTransition = null, ?Closure $afterTransition = null): void
    {
        $beforeTransition && $beforeTransition($this->from, $this->to);
        $this->beforeHook && call_user_func($this->beforeHook, $this->from, $this->to);

        $currentState = $this->to;

        $afterTransition && $afterTransition($this->from, $this->to);
        $this->afterHook && call_user_func($this->afterHook, $this->from, $this->to);
    }
}
