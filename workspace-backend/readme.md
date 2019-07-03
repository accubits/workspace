# Project Name: workspace

## Setup:-

```
git clone https://gitlab.com/accubits/workspace.git
cd workspace
php pathTocomposer/composer.phar install
sudo cp .env.example .env
php artisan key:generate
configure db details in .env file
php artisian module:migrate
```

## References:-
https://nwidart.com/laravel-modules/v2/advanced-tools/artisan-commands

## seeding order
php artisan module:seed UserManagement
php artisan module:seed Common
php artisan module:seed PartnerManagement
php artisan module:seed OrgManagement
