<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('app:make-module {name}')]
#[Description('Command description')]
class MakeModule extends Command
{
    public function handle(): int
    {
        $name = Str::studly($this->argument('name'));
        $folder = Str::pluralStudly($name);
        $routeFile = Str::camel($folder);
        $table = Str::snake(Str::plural($name));

        $paths = [
            app_path("Models/Workflows/{$folder}"),
            app_path("Http/Controllers/Workflows/{$folder}"),
            base_path("routes/Workflows/{$folder}"),
            database_path("migrations/Workflows/{$folder}"),
        ];

        foreach ($paths as $path) {
            File::ensureDirectoryExists($path);
            $this->info("Created: {$path}");
        }

        // ---------------- CREATE REAL LARAVEL FILES ----------------
        $this->call('make:model', [
            'name' => "Workflows/{$folder}/{$name}",
        ]);

        $this->call('make:controller', [
            'name' => "Workflows/{$folder}/Api{$name}Controller",
        ]);

        $this->call('make:controller', [
            'name' => "Workflows/{$folder}/Web{$name}Controller",
        ]);

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--path' => "database/migrations/Workflows/{$folder}",
        ]);
        // ---------------- CREATE REAL LARAVEL FILES ----------------

        $this->putRouteFile(base_path("routes/Workflows/{$folder}/api.{$routeFile}.php"), 'api');
        $this->putRouteFile(base_path("routes/Workflows/{$folder}/web.{$routeFile}.php"), 'web');

        $this->info("Module {$folder} ready.");

        return self::SUCCESS;
    }

    private function putRouteFile(string $path, string $type): void
    {
        if (File::exists($path)) {
            $this->warn("Exists: {$path}");
            return;
        }

        $content = $type === 'api'
            ? "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n"
            : "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n";

        File::put($path, $content);

        $this->info("Created file: {$path}");
    }
}
/*
    RUN: php artisan app:make-module Vehicle
*/