<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Illuminate\Filesystem\FilesystemAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Storage::extend('google', function($app, $config) {
            $options = [];
            if (!empty($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            if (!empty($config['sharedFolderId'])) {
                $options['sharedFolderId'] = $config['sharedFolderId'];
            }

            $client = new \Google\Client();
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            if (!empty($config['serviceAccountJson'])) {
                $client->setAuthConfig($config['serviceAccountJson']);
                $client->addScope(\Google\Service\Drive::DRIVE);
            } else {
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $accessToken = $client->refreshToken($config['refreshToken']);
                $client->setAccessToken($accessToken);
            }
            
            $root = $config['folder'] ?? '/';
            
            $service = new \Google\Service\Drive($client);
            $adapter = new GoogleDriveAdapter($service, $root, $options);
            $driver = new Filesystem($adapter);

            return new FilesystemAdapter($driver, $adapter, $config);
        });
    }
}
