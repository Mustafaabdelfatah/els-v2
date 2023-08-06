<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use LdapRecord\Auth\BindException;
use LdapRecord\Connection;
use LdapRecord\Models\ActiveDirectory\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('welcome');
        $connection = new Connection([
            'hosts' => [env('LDAP_HOST')],
            'port' => env('LDAP_PORT'),
            'base_dn' => env('LDAP_BASE_DN'),
            'username' => env('LDAP_USERNAME'),
            'password' => env('LDAP_PASSWORD'),
            'use_ssl' => env('LDAP_SSL'),
            'use_tls' => env('LDAP_TLS'),
        ]);

        dump($connection->auth()->attempt('legal-svc', 'Ncm$!Ls21', $stayAuthenticated = true), auth()->user());
        dump($connection->auth()->attempt('test2', 'P@ssw0rd'), auth()->user());

        $users = \LdapRecord\Models\ActiveDirectory\User::whereStartsWith('cn', 'legal')->get();

        dd($users);

        try {
            $user = \App\Models\User::find(1);

            $message = $user->name . '  Welcome in legal department app';
            $data = [
                'title' => '',
                'message' => '',
                'from_user_name' => '',
                'from_user_email' => '',
                'from_user_avatar' => '',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '',
                    'type' => '',
                ]
            ];
            dispatch(new \App\Jobs\SendEmail($user, 'mails.email', $data, ''));

            dd("Done");
        } catch (Exception $e) {
            dd($e);
        }
        return view('welcome');
    }

    public function translations(Request $request)
    {

        $translations = [];

//        $app_env = env('APP_ENV') ??  $_ENV['APP_ENV'];
        $app_env = env('APP_ENV') ? env('APP_ENV') :  $_ENV['APP_ENV'];

        if ($app_env == 'local')
//            $directory = '/Applications/MAMP/htdocs/wakeb/NCMS/ncms-version-1.0/Front-End/website-vue/public/lang';
        //    $directory = 'E:/laragon/www/els-v2/Front-End/website-vue/public/lang';
            $directory = 'E:\laragon\www\els-v2\public\vue\locales';
        // else
        //     $directory = 'E:\laragon\www\els-v2\lang';

        if (!is_dir($directory))
            return response()->json(['message' => 'Invalid Directory']);

        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                if (@explode('.', $file)[1] == 'json') {
                    $json = file_get_contents($directory . '/' . $file);

                    $translations[@explode('.', $file)[0]] = json_decode($json);
                }
            }
        }

        return response()->json([
            'translations' => $translations
        ]);
    }

    public function saveTranslations(Request $request)
    {
        $translations = $request->input('translations', []);
        $app_env = env('APP_ENV') ? env('APP_ENV') :  $_ENV['APP_ENV'];

        if ($app_env == 'local')
            $directory = 'C:\inetpub\wwwroot\legal-department-system-lds\public\vue\locales';
        else
            $directory = 'C:\inetpub\wwwroot\legal-department-system-lds\lang';

        if (!is_dir($directory))
            return response()->json(['message' => 'Invalid Directory']);

        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                if (@explode('.', $file)[1] == 'json') {
                    if (@count($translations[@explode('.', $file)[0]])) {
                        $json = json_encode($translations[@explode('.', $file)[0]]);
                        $jsonFile = fopen($directory . '/' . $file, 'w');
                        fwrite($jsonFile, $json);
                        fclose($jsonFile);
                    }
                }
            }
        }

        return response()->json([
            'translations' => $translations
        ]);
    }

}