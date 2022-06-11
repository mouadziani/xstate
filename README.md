### XState - State Machine for PHP
<p align="center" style="margin-top: 1rem; margin-bottom: 1rem;">
    [![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mouadziani/xstate/run-tests?label=tests)](https://github.com/mouadziani/xstate/actions/workflows/run-tests.yml?query=branch%3Amain)
    [![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mouadziani/xstate/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mouadziani/xstate/actions/workflows/php-cs-fixer.yml?query=branch%3Amain)
</p>

XState is a state machine library to play with any complex behavior of your PHP objects

### Installation

The recommended way to install Guzzle is through 
[Composer](https://getcomposer.org/).

```bash
composer require mouadziani/xstate
```

### Define state machine workflow

<p align="center" style="margin-top: 1rem; margin-bottom: 1rem;">
    <img height="400px" src="/art/diagram.png" alt="Video state machine diagram"/>
</p>

Let's say we want to define a state machine workflow for a video object, generally a video may have 3 states (playing, stopped, paused),

as a first step you have to create a new object from `StateMachine` class

```php
use \Mouadziani\XState\StateMachine;

$video = StateMachine::make();
```

Then you have to define the allowed states as well as the default state

```php
$video
    ->defaultState('stopped')
    ->states(['playing', 'stopped', 'paused']);
```

And finally the transitions

```php
use \Mouadziani\XState\Transition;

$video->transitions([
    new Transition('PLAY', ['stopped', 'paused'], 'playing'),
    new Transition('STOP', 'playing', 'stopped'),
    new Transition('PAUSE', 'playing', 'paused'),
    new Transition('RESUME', 'paused', 'playing'),
]);
```

The `Transition` class expect 3 required params:

- **Trigger**: As a name of the transition which will be used to trigger a specific transition *(should be unique)*
- **From**: Expect a string for a single / or array for multiple initial allowed states
- **To**: Expect string which is the next target state *(should match one of the defined allowed states)*

### 💡 You can define the whole workflow with a single statement:

```php 
$video = StateMachine::make()
    ->defaultState('playing')
    ->states(['playing', 'stopped', 'paused'])
    ->transitions([
        new Transition('PLAY', ['stopped', 'paused'], 'playing'),
        new Transition('STOP', 'playing', 'stopped'),
        new Transition('PAUSE', 'playing', 'paused'),
    ]);
```

### Work with states & transitions

#### Trigger transition
There are two ways to trigger a specific defined transition

1- Using `transitionTo` method and specify the name of the transition as an argument

```php
$video->transitionTo('PLAY');
```

2- Or just calling the name of the transition from your machine object as a method

```php
$video->play();
```

Occasionally triggering a transition may throw an exception if the target transition is not defined /or not allowed:

```php
use \Mouadziani\XState\Exceptions;

try {
    $video->transitionTo('RESUME');
} catch (Exceptions\TransitionNotDefinedException $ex) {
    // the target transition is not defined
} catch (Exceptions\TransitionNotAllowedException $ex) {
    // the target transition is not allowed
}
```

#### Get the current state

```php
echo $video->currentState(); // playing
```

#### Get the allowed transitions

```php
$video->allowedTransitions(); // ['STOP', 'PAUSE']
```

#### Adding in-demand transition

```php
$video->addTransition(new Transition('TURN_OFF', 'playing', 'stopped'));
```

## Upcoming features

- [ ] Define/handle hooks before/after triggering transition
- [ ] Add the ability to define gates for a specific transition


## Testing

```bash
composer test
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mouad Ziani](https://github.com/mouadziani)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
