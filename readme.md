

extension a enlever  ; fileinfo dans php.ini


composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load -n