<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard(); // Izslēdz masīva aizsardzību visiem modeļiem, ļaujot masīva piešķiršanu bez aizsardzības
        Model::shouldBeStrict(); // Ieslēdz stingro režīmu visiem modeļiem, kas nozīmē, ka tiks izmesti izņēmumi, ja tiek piešķirtas neesošas atribūtas vai ja tiek piekļūts neesošām attiecībām
        Model::automaticallyEagerLoadRelationships(); // Automātiski ielādē attiecības, kad tiek piekļūts modeļa atribūtam, kas ir attiecība
    }
}
