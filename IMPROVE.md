## 1. Install laravel-ide-helper package
Ref: https://github.com/barryvdh/laravel-ide-helper \
For: `autocompletion` \
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
