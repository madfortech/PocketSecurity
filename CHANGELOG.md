# Changelog

## [1.0.1-alpha] - 2025-12-03
### Added
- `sanitizeOutput()` - HTML escaping added to prevent XSS attacks
- `sanitizeSQL()` - Basic SQL-sanitization helper for extra safety (prepared statements still required)
- `csrfGenerate()` - Secure CSRF token generator using random_bytes()
- `csrfVerify()` - Constant-time token comparison to prevent CSRF attacks
- `destroySession()` - Fully secure session cleanup with cookie invalidation

### Tests
- Added full test suite using pocketphp/pocket-testing
  - XSS sanitization tests
  - SQL sanitization tests
  - CSRF token persistence & verification tests
  - Session destruction tests
  - All tests passing (9/9)