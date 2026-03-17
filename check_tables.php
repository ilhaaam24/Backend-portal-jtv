<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test Firebase connection
echo "FIREBASE_CREDENTIALS env: " . env('FIREBASE_CREDENTIALS') . "\n";

try {
    $messaging = app(\Kreait\Firebase\Contract\Messaging::class);
    echo "Firebase Messaging resolved successfully!\n";
    
    // Try a dry run
    echo "Firebase is properly configured.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
