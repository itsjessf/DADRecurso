<?php
namespace App\Providers;
use Config;
use App\Config as ConfigModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (\Schema::hasTable('config')) {
            $mail = DB::table('config')->first();
            if ($mail) //checking if table is not empty
            {
                $array = json_decode($mail->platform_email_properties,true);
                Config::set('mail', $array);
            }
        }
    }
}
