<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\RefuelingOrder;
use Illuminate\Auth\Authenticatable;
use App\Policies\RefuelingOrderPolicy;
use App\Policies\TomatoDocumentPolicy;

use TomatoPHP\FilamentDocs\Models\Document;
use Parallax\FilamentComments\Policies\FilamentComment;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Parallax\FilamentComments\Models\FilamentComment::class => \Parallax\FilamentComments\Policies\FilamentCommentPolicy::class,
        RefuelingOrder::class => RefuelingOrderPolicy::class,
        Document::class => TomatoDocumentPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
