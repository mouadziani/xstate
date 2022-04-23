<?php

use Mouadziani\XState\StateMachine;
use Mouadziani\XState\Transition;

it('Can get the current state', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('stop', 'playing', 'stopped'),
        ]);

    expect($video->currentState())->toBe('stopped');
});


it('Can be transited', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
        ]);

    $video->transitionTo('STOP');
    expect($video->currentState())->toBe('playing');

    $video->transitionTo('STOP');
    expect($video->currentState())->toBe('stopped');
});
