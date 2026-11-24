<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Log;

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Log::debug("Teste manual de log - " . date('Y-m-d H:i:s'));

echo "✅ Log disparado. Verifique storage/logs.";