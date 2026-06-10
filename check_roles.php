<?php
use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    echo $user->id . ' | ' . $user->email . ' | ' . $user->getRoleNames() . PHP_EOL;
}
