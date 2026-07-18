<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// =======================================================
// VERCEL SERVERLESS FIX
// Vercel filesystem is READ-ONLY, except for /tmp.
// We MUST redirect all writable paths to /tmp BEFORE
// Laravel bootstraps, otherwise Service Providers fail.
// =======================================================

$storagePath = '/tmp/storage';
$bootstrapCache = '/tmp/bootstrap/cache';

// Create all required directories in /tmp
$dirs = [
    $storagePath,
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $bootstrapCache,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set environment variables BEFORE Laravel boots.
// This tells Laravel to use /tmp for all cache files.
$_ENV['APP_SERVICES_CACHE'] = $bootstrapCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $bootstrapCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $bootstrapCache . '/config.php';
$_ENV['APP_ROUTES_CACHE'] = $bootstrapCache . '/routes-v7.php';
$_ENV['APP_EVENTS_CACHE'] = $bootstrapCache . '/events.php';
$_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';

putenv('APP_SERVICES_CACHE=' . $bootstrapCache . '/services.php');
putenv('APP_PACKAGES_CACHE=' . $bootstrapCache . '/packages.php');
putenv('APP_CONFIG_CACHE=' . $bootstrapCache . '/config.php');
putenv('APP_ROUTES_CACHE=' . $bootstrapCache . '/routes-v7.php');
putenv('APP_EVENTS_CACHE=' . $bootstrapCache . '/events.php');
putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path to /tmp
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
