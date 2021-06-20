<?php

namespace App\Policies;

use App\Funcionario;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view_admin(User $user)
    {

        $fun = Funcionario::where('user_id', '=', $user->id)->get();
        if($fun[0]->cargo_id == 0){
            return true;
        }
        else{
            return false;
        }

    }


    public function view_gerenteAdmin(User $user){
        $fun = Funcionario::where('user_id', '=', $user->id)->get();
        //dd($fun[0]->cargo_id);
        if($fun[0]->cargo_id == 1){
            return true;
        }
        else{
            return false;
        }
    }

    public function view_gerenteGeral(User $user){
        //dd(2);

        $fun = Funcionario::where('user_id', '=', $user->id)->get();

        if($fun[0]->cargo_id == 2){
            return true;
        }
        else{
            return false;
        }
    }

    public function view_vendedor(User $user){
        $fun = Funcionario::where('user_id', '=', $user->id)->get();

        if($fun[0]->cargo_id == 3){
            return true;
        }
        else{
            return false;
        }
    }

    public function view_salsicheiro(User $user){
        $fun = Funcionario::where('user_id', '=', $user->id)->get();

        if($fun[0]->cargo_id == 7){
            return true;
        }
        else{
            return false;
        }
    }


    public function view_secretaria(User $user){
        $fun = Funcionario::where('user_id', '=', $user->id)->get();

        if($fun[0]->cargo_id == 4){
            return true;
        }
        else{
            return false;
        }
    }
}
