<?php

namespace App\Console\Commands;

use App\Services\AutoData\AutoInfoExporter;
use Illuminate\Console\Command;

class ExportAutoInfo extends Command
{
    /**
     * Konsolės komandos signatūra.
     */
    protected $signature = 'auto-data:export 
        {path=auto-data : Santykinis katalogas storage diskelyje}
        {--disk=local : Laravel Storage diskas, į kurį rašome}';

    /**
     * Konsolės komandos aprašymas.
     */
    protected $description = 'Eksportuoja automobilio susijusią informaciją į JSON failus.';

    public function handle(AutoInfoExporter $exporter): int
    {
        $path = $this->argument('path');
        $disk = $this->option('disk');

        try {
            $files = $exporter->export($path, $disk);
        } catch (\Throwable $exception) {
            $this->components->error($exception->getMessage());
            return Command::FAILURE;
        }

        $this->components->info('Eksportas atliktas. Sugeneruoti failai:');

        foreach ($files as $file) {
            $this->line(" • {$file}");
        }

        return Command::SUCCESS;
    }
}

