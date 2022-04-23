<?php

use Mouadziani\XState\Exceptions\TransitionNotAllowedException;
use Mouadziani\XState\Exceptions\TransitionNotDefinedException;
use Mouadziani\XState\StateMachine;
use Mouadziani\XState\Transition;

it('can get the current state', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    expect($video->currentState())->toBe('stopped');
});


it('can be transited', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    $video->transitionTo('PLAY');
    expect($video->currentState())->toBe('playing');

    $video->transitionTo('PAUSE');
    expect($video->currentState())->toBe('paused');

    $video->transitionTo('RESUME');
    expect($video->currentState())->toBe('playing');

    $video->transitionTo('PAUSE');
    expect($video->currentState())->toBe('paused');
});


it('throws exception when transition is not allowed', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    $video->transitionTo('PLAY');
    expect($video->currentState())->toBe('playing');

    $video->transitionTo('PLAY');
    expect($video->currentState())->toBe('paused');
})->throws(TransitionNotAllowedException::class);


it('throws exception when transition is not defined', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    $video->transitionTo('TURN_OFF');
})->throws(TransitionNotDefinedException::class);


it('can add new transition', function () {
    $video = StateMachine::make()
        ->defaultState('playing')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', 'stopped', 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
        ]);

    $video->addTransition(new Transition('PAUSE', 'playing', 'paused'));

    $video->transitionTo('PAUSE');

    expect($video->currentState())->toBe('paused');
});
