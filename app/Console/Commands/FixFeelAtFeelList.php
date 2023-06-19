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
        // for ($i = 0; $i < 5; $i++) { 
        //     echo "Hello✋ n"; 
        // }

        $feelList = Feel::all();
        Log::debug('debug', [$feelList]);

        try {
            foreach ($feelList as $feel) {
                $feel_value = $feel->feel;
                switch ($feel_value) {
                 case -5:
                     $feel->feel = 1;
                     break;
                 case -4:
                     $feel->feel = 1;
                     break;
                 case -3:
                     $feel->feel = 2;
                     break;
                 case -2:
                     $feel->feel = 2;
                     break;
                 case -1:
                     $feel->feel = 2;
                     break;
                 case 0:
                     $feel->feel = 3;
                     break;
                 case 1:
                     $feel->feel = 4;
                     break;
                 case 2:
                     $feel->feel = 4;
                     break;
                 case 3:
                     $feel->feel = 4;
                     break;
                 case 4:
                     $feel->feel = 5;
                     break;
                 case 5:
                     $feel->feel = 5;
                     break;
                 default:
                     break;
                }
                Log::debug('debug', [$feel]);
                $feel->save();
             }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }

        return Command::SUCCESS;
    }
}
