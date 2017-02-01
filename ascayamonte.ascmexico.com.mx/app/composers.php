<?php

$user = Session::get('user', null);

View::composer('admin.layouts.master', function($view) use ($user)
{


    $view->with('viewUser', $user);
});