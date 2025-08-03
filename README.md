# PocketSecurity

Security library for Pocket PHP boilerplate

## Installation

```bash
composer require pocketphp/pocketsecurity
```

## Usage

```php
use PocketSecurity\Security;

Security::sanitizeOutput($data);
Security::csrfGenerate();
Security::csrfVerify($token);
Security::sanitizeSQL($input);
Security::destroySession();
```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

