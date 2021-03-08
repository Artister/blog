<?php

namespace Application;

use Artister\System\Configuration\IConfiguration;
use Artister\System\Dependency\IServiceCollection;
use Artister\Web\Dispatcher\IApplicationBuilder;
use Artister\Web\Extensions\ServiceCollectionExtensions;
use Artister\Web\Extensions\ApplicationBuilderExtensions;
use Artister\Entity\Mysql\MysqlEntityOptionsExtension;
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

        $services->addAuthentication();

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
            //Routes::registerRoutes($routes);
            $routes->mapRoute("default", "{controller=Home}/{action=index}/{id?}");
        });
    }
}
