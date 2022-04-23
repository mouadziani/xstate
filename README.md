## XState - A State Machine for PHP

State machine library to play with finite workflows into your php project

## Installation

You can install the package via composer:

```bash
composer require mouadziani/xstate
```

## WIP - Usage

```php

$video = StateMachine::make()
    ->defaultState('stopped')
    ->states(['playing', 'stopped', 'paused'])
    ->transitions([
        new Transition('PLAY', 'stopped', 'playing'),
        new Transition('STOP', 'playing', 'stopped'),
        new Transition('PAUSE', 'playing', 'paused'),
        new Transition('RESUME', 'paused', 'playing'),
    ]);

```

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
