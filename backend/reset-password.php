<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;

$user = App\Models\User::where('email', 'zenesadigital7@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('password123');
    $user->save();
    echo "Password reset to 'password123' for {$user->email}\n";
} else {
    echo "User not found\n";
}
