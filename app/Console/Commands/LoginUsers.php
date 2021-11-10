<?php

namespace App\Console\Commands;

use App\Models\LoginUser;
use App\Models\Variable;
use App\Services\GoogleSheet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoginUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:loginuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los usuarios logeados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle( GoogleSheet $googleSheet )
    {
        $variable = Variable::query()
            ->where('name', 'lastLoggedIDSync')
            ->first();

        $rows = LoginUser::with('user')
            ->where('id', '>', $variable->value)
            ->orderBy('id')
            ->limit(100)
            ->get(); 

        if( $rows->count() === 0 ){
            return  true;
        }

        $finalData = collect();
        $lastId = 0;

        foreach ($rows as $row){            
            $finalData->push([
                $row->id,
                $row->user->email,
                $row->user->fullname,
                $row->user->username,
                $row->user->profesion,
                $row->user->phone,
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }     
        
        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1o1iSUSbA5aV5wMmq6dg9w1Ly2TGDUQDSvzSYSbIvm54',
            'login',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
