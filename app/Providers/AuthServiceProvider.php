<?php

namespace App\Providers;

use App\Models\Gallery;
use App\Policies\GalleryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * एप्लिकेसनका लागि मोडेल र पोलिसी म्यापिङ्ग
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Gallery::class => GalleryPolicy::class,
    ];

    /**
     * कुनै पनि प्रमाणीकरण / अनुमति सेवाहरू रजिस्टर गर्नुहोस्।
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // एडमिन रोल गेट परिभाषित गर्नुहोस्
        Gate::define('access-admin', function ($user) {
            return $user->hasRole('admin');
        });

        // यूजर रोल गेट परिभाषित गर्नुहोस्
        Gate::define('access-user', function ($user) {
            return $user->hasRole('user');
        });

        // अन्य गेटहरू यहाँ थप्न सक्नुहुन्छ
    }
}
