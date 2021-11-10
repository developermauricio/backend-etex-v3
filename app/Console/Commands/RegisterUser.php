<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Variable;
use App\Services\GoogleSheet;
use Illuminate\Console\Command;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:registereduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los usuarios registrados';

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
            ->where('name', 'lastRegisteredIDSync')
            ->first();

        $rows = User::query()
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
                $row->email,
                $row->fullname,
                $row->username,
                $row->profesion,
                $row->phone,
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        }        

        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1h4VxxlmAHxQo_sW1D2bEfHVUR0gjk7NGP4B6TZPb9Gw',
            'register',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
