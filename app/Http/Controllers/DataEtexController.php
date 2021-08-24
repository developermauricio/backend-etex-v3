<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataEtexController extends Controller
{

    public function getCurrentUser() {
       // verificar el usuario actual dentro de wordpress y retornarlo
    }

    public function isAuth() {
       //debe recibir el correo del usuario y devolver si esta autenticado o no
       return response()->json(['status' => 'ok', 'data' => 'si']);
    }
}
