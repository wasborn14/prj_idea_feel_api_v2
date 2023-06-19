<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Feel;

class FixFeelAtFeelList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fixFeel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $feelList = Feel::all();
        try {
            foreach ($feelList as $feel) {
                $feel_value = $feel->value;
                switch ($feel_value) {
                 case -5:
                     $feel->value = 1;
                     break;
                 case -4:
                     $feel->value = 1;
                     break;
                 case -3:
                     $feel->value = 2;
                     break;
                 case -2:
                     $feel->value = 2;
                     break;
                 case -1:
                     $feel->value = 2;
                     break;
                 case 0:
                     $feel->value = 3;
                     break;
                 case 1:
                     $feel->value = 4;
                     break;
                 case 2:
                     $feel->value = 4;
                     break;
                 case 3:
                     $feel->value = 4;
                     break;
                 case 4:
                     $feel->value = 5;
                     break;
                 case 5:
                     $feel->value = 5;
                     break;
                 default:
                     break;
                }
                $feel->save();
             }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }

        return Command::SUCCESS;
    }
}
