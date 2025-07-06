<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/upgrade/database', function () {
    if (config('app.upgrade_mode')) {
        Artisan::call('migrate', ['--force' => true]);

        echo "✅ Upgrade Database has been executed successfully.<br>";
        echo "👉 To prevent accidental reruns, set `<b>UPGRADE_MODE=false</b>` in your <b>.env</b> file.<br>";
    } else {
        echo "🚫 Upgrade Database cannot be executed because `<b>UPGRADE_MODE</b>` is set to false.<br>";
        echo "🔧 If you want to run the database, temporarily set `<b>UPGRADE_MODE=true</b>` in your <b>.env</b> file.";
    }
});
