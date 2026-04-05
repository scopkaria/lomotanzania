<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('email', 'admin@lomotanzania.com')->first();
if ($user) {
    $user->password = bcrypt('admin1234');
    $user->save();
    echo "Password reset successfully.\n";
    echo "Email: " . $user->email . "\n";
    echo "Password: admin1234\n";
} else {
    echo "User not found.\n";
}
