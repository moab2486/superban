## Set up

```
    ** Clone the project to your machine
    ** Run composer install
    ** In the project you will like to test the package
        add  the following to your composer.json
        "repositories": [
            {
                "type": "path",
                "url": "relative/or/absolute/path/to/your/local/package"
                "options": {
                    "symlink": true
                }
            }
        ],
        "require": {
            "abdulkadir/super-ban": "*"
        }
    ** To install the package run
        cache driver (database): php artisan superban:install --driver=database
        cache driver (redis): php artisan superban:install --driver=

    ** To test package run
        Test: php artisan test
```