# laravel-static-site

This package will help you burn some pages to generate a static site for CDN deployment.

## Configuration

To customize the routes you want to burn, publish the configuration file.
```
php artisan vendor:publish --tag=static-site-config
```

Add a disk to your `filesystems` configuration.

For development

```php
'static-site' => [
    'driver' => 'local',
    'root' => storage_path('app/public/static-site'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
    'throw' => false,
],
```

For production:

```php
'static-site' => [
    'driver' => 's3', // 👈
    'key' => env('CLOUDFLARE_R2_ACCESS_KEY'),
    'secret' => env('CLOUDFLARE_R2_SECRET_KEY'),
    'region' => 'auto',
    'bucket' => env('CLOUDFLARE_R2_BUCKET'),
    'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
    'url' => env('CLOUDFLARE_R2_URL'),
],
```

## Running

To start burning and deploying run the following commands.

```
yarn run build
php artisan burn:assets
php artisna burn:site
```

## Testing locally

For quick testing of what was burned you could start a php server and browse around.

```
php -S localhost:8888 -t storage/app/public/static-site/
```
