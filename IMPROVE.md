## 1. Install laravel-ide-helper package
Ref: https://github.com/barryvdh/laravel-ide-helper \
For: `Autocompletion` \
Usage: Re-generate the docs yourself (for future updates)
```
composer run ide-helper
```

## 2. Install laravel/telescope package
Ref: https://laravel.com/docs/9.x/telescope \
For: `Debug` database queries,...\
Usage: \
Add new telescope tables (in the `first time`)
```
php artisan migrate
```
Access the URL `http://127.0.0.1:8000/telescope`, click the target request for showing the request details \
Note: Only used in `local` environment now

## 3. Install nunomaduro/larastan package
Ref: https://github.com/nunomaduro/larastan \
For: Code quality (`error, type`) \
Usage: Run the command below for checking errors
```
composer run analyse
```
