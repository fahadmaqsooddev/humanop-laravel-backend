<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebPagesController extends Controller
{
    protected $question = null;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }
}
