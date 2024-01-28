<?

namespace Daisnurfaizi\Builder;

use Illuminate\Support\ServiceProvider;

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
