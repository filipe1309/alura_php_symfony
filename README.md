## No Diretorio da app:

// Usar img docker do composer para criar projeto
$ docker run --rm -itv %CD%:/app -w /app composer create-project symfony/skeleton consultorio-alura

// Usar img docker do php para rodar projeto
$ cd consultorio-alura
$ docker run --rm -itv %CD%:/app -w /app -p 8080:8080 php -S 0.0.0.0:8080 -t public

$ composer require annotattion

## ORM Doctrine
$ composer require orm-pack

No . env
DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db

$ bin/console doctrine:database:create

$ bin/console doctrine:migrations:diff

$ bin/console doctrine:migrations:migrate

## From Sqlite to Mysql

### In Windows, initialize MySQL80 Service, on Services (if not yet)

### Dentro do container php
$ bin/console doctrine:database:create

$ bin/console doctrine:migrations:diff

$ bin/console doctrine:migrations:execute --up 'DoctrineMigrations\Version20201006004936'
