<?php

namespace App\Providers;

use App\Events\CreatePelatihan;
use App\Events\KatalogEvent;
use App\Events\ProcessPelatihan;
use App\Listeners\PublishKatalog;
use App\Listeners\StoreJabatan;
use App\Listeners\StoreLogPelatihan;
use App\Listeners\StorePendaftaran;
use App\Listeners\UpdateStatusPelatihan;
use App\Listeners\UploadBahan;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        KatalogEvent::class => [
            StoreJabatan::class,
            UploadBahan::class,
            PublishKatalog::class
        ],

        CreatePelatihan::class => [
            StorePendaftaran::class,
            StoreLogPelatihan::class,
        ],
        ProcessPelatihan::class => [
            UpdateStatusPelatihan::class,
            StoreLogPelatihan::class,
            StorePendaftaran::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
