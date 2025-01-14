<?php
use Illuminate\Support\Facades\Auth;



function username()
{
    return Auth::User()->name;
}

function userEmail()
{
    return Auth::User()->email;
}

