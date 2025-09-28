<?php

namespace App\Http\Controllers\Integration;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationIntegrationController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
    }
}
