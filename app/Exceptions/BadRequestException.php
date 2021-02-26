<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
    public function render(Request $request)
    {
        return redirect('/')->withErrors([$this->getMessage()]);
    }
}
