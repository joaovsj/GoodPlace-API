<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // private bool $status   = true;
    // private array $details = [];
    
    // public function output(){
    //     return [
    //         'status'    => $this->status,
    //         'details'   => $this->details
    //     ];
    // }

}
