<?php

namespace App\Console\Commands;

use App\Models\TypeWall;
use App\Models\Variable;
use App\Services\GoogleSheet;
use Illuminate\Console\Command;

class TypeWallsView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:typewalls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando permite poder sincronizar los datos de los tipos de muros visitados';

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
            ->where('name', 'lastTypeWallIDSync')
            ->first();

        $rows = TypeWall::with('user')
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
                $row->date_register,
                $row->created_at,
            ]);

            $lastId = $row->id;
        } 
        
        $googleSheet->saveDataToSheet(
            $finalData->toArray(),
            '1ofUG4QheLz_VRrgpV-O-B6ozTjKqw4WMiJsej9XImOQ',
            'type_walls',
        );

        $variable->value = $lastId;
        $variable->save();

        return true;
    }
}
