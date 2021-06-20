<?php

namespace Application;

use DevNet\Core\Configuration\IConfiguration;
use DevNet\Core\Dependency\IServiceCollection;
use DevNet\Core\Dispatcher\IApplicationBuilder;
use DevNet\Core\Extensions\ServiceCollectionExtensions;
use DevNet\Core\Extensions\ApplicationBuilderExtensions;
use DevNet\Entity\Providers\EntityOptionsExtensions;
use Application\Models\DbManager;
use Application\Models\User;

class Startup
{
    private IConfiguration $Configuration;

    public function __construct(IConfiguration $configuration)
    {
        $this->Configuration = $configuration;
    }

    public function configureServices(IServiceCollection $services)
    {
        $services->addMvc();
        
        $services->addAntiforgery();

        $services->addAuthentication(function($options)
        {
            $options->LoginPath = '/user/account/login';
        });

        $services->addAuthorisation();

        $services->addEntityContext(DbManager::class, function($options)
        {
            $options->useMysql("//root:root@127.0.0.1/blog");
        });

        $services->addIdentity(User::class);
    }

    public function configure(IApplicationBuilder $app)
    {
        $app->UseExceptionHandler();

        $app->useRouter();

        $app->useAuthentication();
        
        $app->useAuthorization();
        
        $app->useEndpoint(function($routes)
        {
            $routes->mapRoute("user", "user/{controller=Account}/{action=Index}/{id?}");
            $routes->mapRoute("admin", "admin/{controller=Dashboard}/{action=Index}/{id?}");
            $routes->mapRoute("default", "{controller=Home}/{action=Index}/{id?}");
        });
    }
}
