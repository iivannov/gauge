<?php

namespace Iivannov\Gauge\Commands;

use Illuminate\Console\Command;

class Toggle extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'gauge {--down} {--up}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable or disable Gauge';


    public function handle()
    {
        if ($this->option('down')) {
            $this->stop();
        } else if ($this->option('up')) {
            $this->start();
        } else {
            $this->info('Choose either to --up or --down');
        }
    }

    private function stop()
    {
        @unlink(storage_path('/framework/gauge'));

        $this->info('Gauge is disabled.');
    }

    private function start()
    {
        file_put_contents(storage_path('/framework/gauge'),json_encode(['time' => time()]));

        $this->info('Gauge is enabled.');
    }

}
