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
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    expect($video->currentState())->toBe('stopped');
});


it('can change machine state after triggering a transition', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
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


it('can add new transition', function () {
    $video = StateMachine::make()
        ->defaultState('playing')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
        ]);

    $video->addTransition(new Transition('PAUSE', 'playing', 'paused'));

    $video->transitionTo('PAUSE');

    expect($video->currentState())->toBe('paused');
});

it('can get allowed transitions', function () {
    $video = StateMachine::make()
        ->defaultState('playing')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
        ]);

    expect($video->allowedTransitions())->toMatchArray(['STOP', 'PAUSE']);

    $video->transitionTo('STOP');

    expect($video->allowedTransitions())->toBe(['PLAY']);
});


it('can trigger transitions from machine object as method', function () {
    $video = StateMachine::make()
        ->defaultState('playing')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
        ]);

    $video->stop();
    expect($video->currentState())->toBe('stopped');

    $video->play();
    expect($video->currentState())->toBe('playing');

    $video->pause();
    expect($video->currentState())->toBe('paused');
});


it('throws exception when transition is not allowed', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
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
            new Transition('PLAY', ['stopped', 'paused'], 'playing'),
            new Transition('STOP', 'playing', 'stopped'),
            new Transition('PAUSE', 'playing', 'paused'),
            new Transition('RESUME', 'paused', 'playing'),
        ]);

    $video->transitionTo('TURN_OFF');
})->throws(TransitionNotDefinedException::class);

it('Uses guard to check if the transition is allowed', function () {
    $video = StateMachine::make()
        ->defaultState('stopped')
        ->states(['playing', 'stopped', 'paused'])
        ->transitions([
            (new Transition('PLAY', ['stopped', 'paused'], 'playing'))
                ->guard(fn ($from, $to) => false),

            (new Transition('TURN_ON', ['stopped', 'paused'], 'playing'))
                ->guard(fn ($from, $to) => true),
        ]);

    expect(false)->toBe($video->canTransisteTo('PLAY'));
    expect(true)->toBe($video->canTransisteTo('TURN_ON'));

    $video->transitionTo('TURN_ON');
    expect($video->currentState())->toBe('playing');
});
