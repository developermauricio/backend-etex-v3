<?php

namespace App\Console\Commands;

use App\Models\ModelFile;
use App\Models\Variable;
use App\Services\GoogleSheet;
use Illuminate\Console\Command;

class FilesModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:filesmodels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los archivos de los modelos 3D';

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
            ->where('name', 'lastFilesModelIDSync')
            ->first();
        
        $rows = ModelFile::with('user')
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
                $row->name,
                $row->title,
                $row->url_file,
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        } 
               
        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1HvLQ-hQZpQTuw7LbvxQ_mnJvrKn5_SOJvuWS3VW6A60',
            'docs',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
