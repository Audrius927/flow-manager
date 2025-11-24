<?php

namespace App\Console\Commands;

use App\Services\AutoData\AutoInfoImporter;
use Illuminate\Console\Command;

class ImportAutoInfo extends Command
{
    protected $signature = 'auto-data:import 
        {path=auto-data : Santykinis katalogas storage diskelyje}
        {--disk=local : Laravel Storage diskas, iš kurio skaitome}
        {--no-truncate : Nevalyti lentelių prieš importą}';

    protected $description = 'Importuoja JSON failus į bazę (markės, modeliai, kategorijos ir kt.).';

    public function handle(AutoInfoImporter $importer): int
    {
        $path = $this->argument('path');
        $disk = $this->option('disk');
        $shouldTruncate = !$this->option('no-truncate');

        try {
            $counts = $importer->import($path, $disk, $shouldTruncate);
        } catch (\Throwable $exception) {
            $this->components->error($exception->getMessage());
            return Command::FAILURE;
        }

        $this->components->info('Importas atliktas:');

        foreach ($counts as $label => $count) {
            $this->line(" • {$label}: {$count}");
        }

        return Command::SUCCESS;
    }
}

