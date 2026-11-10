<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

#[Signature('app:delete-module {name} {--force}')]
#[Description('Delete module folders and files')]
class DeleteModule extends Command
{
   public function handle(): int
   {
      $name = Str::studly($this->argument('name'));
      $folder = Str::pluralStudly($name);

      $paths = [
         app_path("Models/Workflows/{$folder}"),
         app_path("Http/Controllers/Workflows/{$folder}"),
         base_path("routes/Workflows/{$folder}"),
         database_path("migrations/Workflows/{$folder}"),
      ];

      if (!$this->option('force')) {
         if (!$this->confirm("Delete module {$folder}?")) {
            $this->warn("Cancelled.");
            return self::FAILURE;
         }
      }

      foreach ($paths as $path) {
         if (File::exists($path)) {
            File::deleteDirectory($path);
            $this->info("Deleted: {$path}");
         } else {
            $this->warn("Not found: {$path}");
         }
      }

      $this->info("Module {$folder} removed.");

      return self::SUCCESS;
   }
}
/*
    RUN: php artisan app:delete-module Vehicle --force
*/