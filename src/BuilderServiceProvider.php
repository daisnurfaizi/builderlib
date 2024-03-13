<?

namespace daisnurfaizi\bubuilder;

use Illuminate\Support\ServiceProvider;
use daisnurfaizi\bubuilder\GenerateEntity;
use daisnurfaizi\bubuilder\GenerateBuilder;


class BuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Boot logic, if needed
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateEntity::class,
                GenerateBuilder::class,
            ]);
        }
    }
}
