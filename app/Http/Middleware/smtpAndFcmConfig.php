<?php

namespace App\Http\Middleware;
use App\SmsEmailNotification;
use Closure;

class smtpAndFcmConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $database = SmsEmailNotification::where('type','fcm' )->first();

        /**
         * undocumented constant
         **/
        #SMTP
        config(['mail.driver'       => 'smtp']);
        config(['mail.host'         => $database->host]);
        config(['mail.port'         => $database->port]);
        config(['mail.from.address' => $database->sender_email]);
        config(['mail.from.name'    => $database->sender_name]);
        config(['mail.encryption'   => $database->encryption]);
        config(['mail.username'     => $database->username]);
        config(['mail.password'     => $database->password]);

        #FCM
        config(['fcm.http.server_key' => $database->server_key]);
        config(['fcm.http.sender_id'  => $database->sender_id]);


        return $next($request);
    }
}
