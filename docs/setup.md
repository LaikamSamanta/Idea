# Idea — Laravel Aplikācija

## Klonēšana no GitHub

```bash
git clone https://github.com/LaikamSamanta/Idea
cd Idea
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Papildu rīki

```bash
# Pest — testēšana
composer require pestphp/pest --dev
php artisan pest:install

# Rector — koda refaktorings
composer require rector/rector --dev
composer require --dev driftingly/rector-laravel
vendor/bin/rector init

# Frontend
npm install
npm run dev        # izstrādei
npm run build      # produkcijai
```

### Palaišana

```bash
php artisan test        # vai
./vendor/bin/pest

php artisan serve       # ja nelieto Laravel Herd
```

---

## Izveide no nulles

### 1. Projekta izveide un Git inicializācija

```bash
laravel new idea --pest
cd idea
git init
git add .
git commit -m "Initial commit"
```

Izveido jaunu repozitoriju GitHub un pievieno remote origin pēc GitHub norādījumiem.

---

### 2. Koda formatēšanas rīki

#### `composer.json` — scripts sekcija

Pievieno šo komandu, lai formatētu kodu ar Rector un Pint vienā solī:

```json
"scripts": {
    "format": [
        "vendor/bin/rector",
        "vendor/bin/pint"
    ]
}
```

Palaišana:

```bash
composer run format
```

#### Rector instalācija

```bash
composer require rector/rector --dev
vendor/bin/rector init
composer require --dev driftingly/rector-laravel
```

---

### 3. `rector.php` konfigurācija

Nomainīt ģenerēto `rector.php` ar šo:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;
use Rector\TypeDeclaration\Rector\Closure\AddClosureVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/public',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/bootstrap/cache',
        __DIR__.'/storage',
        __DIR__.'/vendor',
        AddClosureVoidReturnTypeWhereNoReturnRector::class,
        ReturnTypeFromStrictTypedCallRector::class,
        ReturnUnionTypeRector::class,
        DeclareStrictTypesRector::class => [
            __DIR__.'/resources/views',
        ],
        AddArrowFunctionReturnTypeRector::class,
    ])
    ->withPhpSets()
    ->withSetProviders(LaravelSetProvider::class)
    ->withImportNames()
    ->withComposerBased(laravel: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
    )
    ->withRules([
        DeclareStrictTypesRector::class,
    ]);
```