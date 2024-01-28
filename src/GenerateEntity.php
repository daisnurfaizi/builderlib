<?

namespace daisnurfaizi\bubuilder;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:entity {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Entity class from model fillable attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the model class name from the command argument
        $modelClassName = $this->argument('model');

        // Extract namespace and class name
        $modelParts = explode('\\', $modelClassName);
        $modelName = end($modelParts);
        $modelNamespace = implode('\\', array_slice($modelParts, 0, -1));

        // Construct the model class path
        $modelClassPath = app_path('Models/' . str_replace('\\', '/', $modelNamespace) . '/' . $modelName . '.php');

        // Check if the model class file exists
        if (!file_exists($modelClassPath)) {
            $this->error("The $modelClassName class does not exist in the app/Models directory!");
            return;
        }


        // Dynamically create the model class path
        $fullModelClassPath = "App\\Models\\" . str_replace('/', '\\', $modelNamespace) . '\\' . $modelName;
        dd($fullModelClassPath);

        // Include the model class file
        $model = new $fullModelClassPath;

        // Use Reflection to get the fillable attributes of the model
        $fillableAttributes = $model->getFillable();

        // Generate entity path and name
        $entityParts = explode('/', $modelClassName . 'Entity');
        $entityName = end($entityParts);
        $entityPath = app_path('Http/Entity/' . implode('/', array_slice($entityParts, 0, -1)));

        $properties = '';
        $methods = '';

        foreach ($fillableAttributes as $attribute) {
            $properties .= "\tprivate \$$attribute;\n";
            $camelCase = ucfirst(Str::camel($attribute));

            // getter method
            $methods .= "\n\t/**\n\t * Get the $attribute.\n\t *\n\t * @return mixed\n\t */\n\tpublic function get$camelCase()\n\t{\n\t\treturn \$this->$attribute;\n\t}\n\n";

            // setter method
            $methods .= "\n\t/**\n\t * Set the $attribute.\n\t *\n\t * @return mixed\n\t */\n\tpublic function set$camelCase(\$value)\n\t{\n\t\t\$this->$attribute = \$value;\n\t}\n\n";
        }

        $classTemplate = "<?php\n\nnamespace App\Http\Entity" . implode('\\', array_slice($entityParts, 0, -1)) . ";\n\nclass $entityName\n{\n$properties\n\t// Constructor\n\tpublic function __construct(" . implode(', ', array_map(function ($attr) {
            return "\$$attr";
        }, $fillableAttributes)) . ")\n\t{\n" . implode("\n", array_map(function ($attr) {
            return "\t\t\$this->$attr = \$$attr;";
        }, $fillableAttributes)) . "\n\t}\n\n$methods\t// Other necessary methods...\n}\n";

        File::ensureDirectoryExists($entityPath);
        File::put($entityPath . '/' . $entityName . '.php', $classTemplate);

        $this->info("$entityName class generated successfully!");
    }
}
