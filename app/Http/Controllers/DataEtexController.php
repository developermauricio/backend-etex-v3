<?php

namespace App\Http\Controllers;

use App\Models\EventClick;
use App\Models\User;
use App\Models\Scene;
use App\Models\TypeWall;
use App\Models\Wall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataEtexController extends Controller
{
   public function registerSceneVisit( Request $request ) {  
      $currentUser = User::whereEmail( $request->email )->first();

      if ( !$currentUser ) {  
         return response()->json(['status' => 'fail', 'msg' => 'registro fallido el usuario no está registrado.']);         
      } 

      $this->registerClick( $currentUser->id, $request );

      $listScenes = Scene::where('user_id', $currentUser->id)->get();

      if ( $listScenes ) {
         $dateNow = Carbon::now('America/Bogota');
         $existRegister = false;

         foreach ( $listScenes as $scene ) {
            $dateRegister = new Carbon($scene->created_at, 'America/Bogota');

            if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->nameScene == $scene->name_scene ) { 
               $existRegister = true;
               break;
            }
         }

         if ( $existRegister ) {
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
         }
      } 

      DB::beginTransaction();
      try {  
          
         $newScene = new Scene;      
         $newScene->user_id = $currentUser->id; 
         $newScene->name_scene = $request->nameScene; 
         $newScene->date_register = Carbon::now('America/Bogota');
         $newScene->save();

         DB::commit();

         return response()->json(['status' => 'ok', 'msg' => 'registro agredado correctamente.']);

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
      } 
   } 

   public function registerClick( $idUser, $request ) {        
      $listCliks = EventClick::where('user_id', $idUser)->get();

      if ( $listCliks ) {
         $dateNow = Carbon::now('America/Bogota');
         $existRegister = false;

         foreach ( $listCliks as $click ) {
            $dateRegister = new Carbon($click->created_at, 'America/Bogota');

            // se compara el dia, nombre de la scena y nombre del click, para guardar informacion duplicada.
            if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->nameScene == $click->name_scene && $request->click == $click->name_click ) { 
               $existRegister = true;
               break;
            }
         }

         if ( $existRegister ) {
            return false;
         }
      } 
      
      DB::beginTransaction();
      try {  
          
         $newClick = new EventClick;      
         $newClick->user_id = $idUser; 
         $newClick->name_scene = $request->nameScene; 
         $newClick->name_click = $request->click; 
         $newClick->date_register = Carbon::now('America/Bogota');
         $newClick->save();

         DB::commit();
         return true;

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return false;
      } 
   } 

   public function registerWall( Request $request ) {  
      $currentUser = User::whereEmail( $request->email )->first();

      if ( !$currentUser ) {  
         return response()->json(['status' => 'fail', 'msg' => 'registro fallido el usuario no está registrado.']);         
      } 

      $this->registerClick( $currentUser->id, $request );

      if ( $request->wall == "" ) {
         return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
      }

      $this->registerTypeWallDB($currentUser->id, $request); 

      $listWalls = Wall::where('user_id', $currentUser->id)->get();

      if ( $listWalls ) {
         $dateNow = Carbon::now('America/Bogota');
         $existRegister = false;

         foreach ( $listWalls as $wall ) {
            $dateRegister = new Carbon($wall->created_at, 'America/Bogota');

            //  se compara el dia, nombre de la escena y el nombre del muro
            if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->nameScene == $wall->name_scene && $request->wall == $wall->name_wall) { 
               $existRegister = true;
               break;
            }
         }

         if ( $existRegister ) {
            return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
         }
      } 

      DB::beginTransaction();
      try {  
          
         $newWall = new Wall;      
         $newWall->user_id = $currentUser->id; 
         $newWall->name_scene = $request->nameScene; 
         $newWall->name_wall = $request->wall; 
         $newWall->date_register = Carbon::now('America/Bogota');
         $newWall->save();

         DB::commit();
         return response()->json(['status' => 'ok', 'msg' => 'registro agredado correctamente.']);

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return response()->json(['status' => 'fail', 'msg' => 'registro fallido.']);
      } 
   } 

   public function registerTypeWallDB( $idUser, $request ) {  
      if ($request->typeWall == "") {
         return false;
      }
      
      $listTypeWalls = TypeWall::where('user_id', $idUser)->get();

      if ( $listTypeWalls ) {
         $dateNow = Carbon::now('America/Bogota');
         $existRegister = false;

         foreach ( $listTypeWalls as $typeWall ) {
            $dateRegister = new Carbon($typeWall->created_at, 'America/Bogota');

            // se compara el dia, nombre de la scena, nombre del muro y tipo de muro, para guardar informacion duplicada.
            if ( $dateNow->dayOfYear == $dateRegister->dayOfYear && $request->nameScene == $typeWall->name_scene && $request->wall == $typeWall->name_wall && $request->typeWall == $typeWall->type_wall ) { 
               $existRegister = true;
               break;
            }
         }

         if ( $existRegister ) {
            return false;
         }
      } 

      DB::beginTransaction();
      try {  
          
         $newTypeWall = new TypeWall;      
         $newTypeWall->user_id = $idUser; 
         $newTypeWall->name_scene = $request->nameScene; 
         $newTypeWall->name_wall = $request->wall; 
         $newTypeWall->type_wall = $request->typeWall; 
         $newTypeWall->date_register = Carbon::now('America/Bogota');
         $newTypeWall->save();

         DB::commit();
         return true;

      } catch (\Exception $exception) {
         DB::rollBack();
         Log::debug($exception->getMessage());
         return false;
      } 
   } 


   public function getScenesVisit() {
      $listScenes = Scene::all();
      return response()->json(['status' => 'ok', 'data' => $listScenes]);
   }

   public function getEventClicks() {
      $listClicks = EventClick::all();
      return response()->json(['status' => 'ok', 'data' => $listClicks]);
   }

   public function getWalls() {
      $listWalls = Wall::all();
      return response()->json(['status' => 'ok', 'data' => $listWalls]);
   }

   public function getTypeWalls() {
      $listTypeWalls = TypeWall::all();
      return response()->json(['status' => 'ok', 'data' => $listTypeWalls]);
   }



   public function getCurrentUser() {
      // verificar el usuario actual dentro de wordpress y retornarlo
   }

   public function isAuth() {
      //debe recibir el correo del usuario y devolver si esta autenticado o no
      return response()->json(['status' => 'ok', 'data' => 'si']);
   }
}
