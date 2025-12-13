<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Listen to slow queries
        DB::listen(function ($query){
            //threshold 1000ms = 1 sec
            if($query->time > 1000){
                //Formar the SQL with bindings
                $sql = $query->sql;
                foreach ($query->bindings as $binding){
                    $value = is_numeric($binding) ? $binding: "'{$binding}'";
                    $sql = preg_replace('/\?/', $value, $sql, 1);
                }

                $message = "🐢 SLOW QUERY DETECTED: ({$query->time}ms)\nSQL: {$sql}\nURL: " . request()->fullUrl();

                //Log to daily file
                Log::channel('daily')->warning($message);

                //Alert Admins via Slack (using 'Emergency' or 'Critical' to trigger Slack)
                try {
                    Log::channel('slack')->critical($message);
                } catch (\Exception $e){
                    //Fail silently so the app does not crash
                }
            }
        });
    }
}
