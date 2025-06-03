# Symfony 7.1 

## Requirements

- docker

## Setup and run

### Configuration

By default, just copy `compose.override.dist.yaml` in `compose.override.yaml` before anything else.

```bash
make install # Build, setup and run application
```

### Run

If application was already setup once, just run:

```bash
make start
```

### Database

If a reset of database is needed:

```bash
make database/setup database/load
```

## Exploitables

### [CVE-2024-51996 (Bypass authentication via persisted rememberme cookie)](https://symfony.com/blog/cve-2024-51996-authentication-bypass-via-persisted-rememberme-cookie)

#### Exploit

1. Browse to https://127.0.0.1:8000/login and login with the basic user `user:user`
2. Get the rememberme cookie in javascript console with: `document.cookie`
3. Alter cookie with the "User fragment" value between 2 first %3A (%3A is ":" urlencoded)
4. Open a second non authentified browser and inject altered cookie, refresh, is pwned.

#### Explanation

Persisted cookie are fetched using a "serie" as key, serie is a random string generated when the cookie is created (could be found as 4th value in cookie if you split on "%3A").<br>
If this value is retrieved and the cookie isn't expired, other value aren't actually checked, so you can inject username you want to use. 

#### Automatic exploit

```
make console c="exploit:CVE-2024-51996 admin"
```

Copy PHPSESSID output in your browser cookie to get `admin` account access without password.


