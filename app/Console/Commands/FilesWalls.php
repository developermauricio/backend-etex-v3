<?php

namespace App\Console\Commands;

use App\Models\FileWall;
use App\Models\Variable;
use App\Services\GoogleSheet;
use Illuminate\Console\Command;

class FilesWalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:fileswalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los archivos vistos';

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
            ->where('name', 'lastFilesIDSync')
            ->first();

        $rows = FileWall::with('user')
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
                $row->name_scene,
                $row->name_wall,
                $row->type_wall,
                $row->name_file,
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        } 
               
        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1tQJrNCdbucZFp__44PxuySIgceQp8oXNU6vH8tAFp3Y',
            'files',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
