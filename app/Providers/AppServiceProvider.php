<?php

namespace App\Providers;

use App\Models\Agency;
use Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(!app()->runningInConsole()) {

            $agencies = Cache::rememberForever('settings', function() {
                return \App\Models\Agency::get();
            });

            $agency = $agencies->where('domain', get_domain())->first();

            if(!$agency) return die('Unauthorized Domain Name ' . get_domain());

            Config::set('app.name', $agency->name);
            Config::set('app.url', 'https://' . $agency->domain);

            if(config('app.enable_emails')) {
                Config::set('mail.mailers.smtp.host', $agency->mail_host);
                Config::set('mail.mailers.smtp.username', $agency->mail_username);
                Config::set('mail.mailers.smtp.port', $agency->mail_port);
                Config::set('mail.mailers.smtp.password', $agency->mail_password);
                Config::set('mail.mailers.smtp.encryption', $agency->mail_encryption);
                Config::set('mail.mailers.smtp.from.address', $agency->mail_sender_email);
                Config::set('mail.mailers.smtp.from.name', $agency->mail_sender_name);
                Config::set('mail.mailers.smtp.mailer', 'smtp');
            }

            view()->composer('*', function($view) use ($agency) {
                $view->with('site_settings', $agency);
                if(!is_null($agency)) {
                    $config = array(
                        'driver'     =>     $agency->mail_driver,
                        'host'       =>     $agency->mail_host,
                        'port'       =>     $agency->mail_port,
                        'username'   =>     $agency->mail_username,
                        'password'   =>     $agency->mail_password,
                        'encryption' =>     $agency->mail_encryption,
                        'from'       =>     array('address' => $agency->sender_email, 'name' => $agency->sender_name),
                    );
                    if(config('app.enable_emails')) {
                        Config::set('mail', $config);
                    }
                    Config::set('agency', $agency);
                }
            });
        }
    }
}
