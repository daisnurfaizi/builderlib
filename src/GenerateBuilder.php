<?php

namespace daisnurfaizi\bubuilder;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionProperty;
use Illuminate\Support\Str;

class GenerateBuilder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:builder {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Builder class from entity attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the entity class name from the command argument
        $entityClassName = $this->argument('entity');

        // Extract namespace and class name
        $entityParts = explode('/', $entityClassName);
        $entityName = end($entityParts);
        $entityNamespace = implode('\\', array_slice($entityParts, 0, -1));
        // Check if the entity class file exists
        $entityClassPath = app_path('Http/Entity/' . str_replace('\\', '/', $entityNamespace) . '/' . $entityName . '.php');

        if (!file_exists($entityClassPath)) {
            $this->error("The $entityClassPath class does not exist in the app/Models directory!");
            return;
        }
        // Include the entity class file
        // require_once $entityClassPath;
        $fullModelClassPath = "App\\Http\\Entity\\" . str_replace('/', '', $entityNamespace) . '\\' . $entityName;
        // dd($fullModelClassPath);
        // Use Reflection to get the fillable attributes of the entity
        $reflectionClass = new ReflectionClass($fullModelClassPath);
        $fillableAttributes = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        $parts = explode('/', $entityClassName);
        $builderName = end($parts) . 'Builder';
        $builderPath = app_path('Http/Builder/' . implode('/', array_slice($parts, 0, -1)));
        $properties = '';
        $methods = '';

        foreach ($fillableAttributes as $attribute) {
            $propertyName = $attribute->getName();
            $properties .= "\tprivate \$$propertyName;\n";
            $camelCase = ucfirst(Str::camel($propertyName));
            $methods .= "\n\t/**\n\t * Set the $propertyName.\n\t *\n\t * @param mixed \$$propertyName\n\t * @return \$this\n\t */\n\tpublic function set$camelCase(\$$propertyName)\n\t{\n\t\t\$this->$propertyName = \$$propertyName;\n\t\treturn \$this;\n\t}\n\n";
        }

        // Create the builder class file
        $classTemplate = "<?php\n\nnamespace App\\Http\\Builder\\" . implode('\\', array_slice($parts, 0, -1)) . ";\nuse $fullModelClassPath;\n\nclass $builderName\n{\n$properties\n$methods\t// Other necessary methods...\n\n\t/**
    \t * Build an instance of the entity with the values set in the builder.
    \t *
    \t * @return $entityName
    \t */
    \tpublic function build()
    \t{
    \t\treturn new $entityName(\n";

        foreach ($fillableAttributes as $attribute) {
            $propertyName = $attribute->getName();
            $classTemplate .= "\t\t\t\$this->$propertyName,\n";
        }

        $classTemplate .= "\t\t);\n\t}\n}\n";




        // Check if the builder class file already exists
        File::ensureDirectoryExists($builderPath);
        File::put($builderPath . '/' . $builderName . '.php', $classTemplate);
        // info message
        $this->info("The $builderName class has been generated in the app/Http/Builder directory!");
    }
}
