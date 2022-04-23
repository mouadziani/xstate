<?php

namespace Mouadziani\XState;

use Closure;

class Transition
{
    public string $trigger;
    public string $from;
    public string $to;
    public ?Closure $beforeHook;
    public ?Closure $afterHook;

    public function __construct(string $trigger, string $from, string $to, ?Closure $beforeHook = null, ?Closure $afterHook = null)
    {
        $this->trigger = $trigger;
        $this->from = $from;
        $this->to = $to;
        $this->beforeHook = $beforeHook;
        $this->afterHook = $afterHook;
    }

    public static function fromArray(array $options): self
    {
        return new static($options['trigger'], $options['from'], $options['to'], $options['beforeHook'], $options['afterHook']);
    }
}