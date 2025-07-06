<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';
    protected $description = 'Backup database ke file SQL';

    public function handle()
    {
        $db = config('database.connections.mysql');
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'));
        }

        $command = "mysqldump -u {$db['username']} -p'{$db['password']}' {$db['database']} > {$path}";

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $this->info("Backup berhasil disimpan di: {$path}");
        } else {
            $this->error('Gagal melakukan backup database.');
        }
    }
}
