<?php
/**
 * Cookie authorizer
 * @package lib-user-auth-cookie
 * @version 0.0.1
 */

namespace LibUserAuthCookie\Authorizer;

use LibUserAuthCookie\Model\UserAuthCookie as UACookie;

class Cookie implements \LibUser\Iface\Authorizer
{
    private static $session;

    static function getSession(): ?object{
        return self::$session;
    }
    
    static function identify(): ?string{
        $config = \Mim::$app->config->libUserAuthCookie;
        $cookie_name = $config->cookie;
        $cookie_expires = $config->expires;

        $hash = \Mim::$app->req->getCookie($cookie_name);
        if(!$hash)
            return null;

        $session = UACookie::getOne(['hash'=>$hash]);
        if(!$session)
            return null;

        // session expired?
        $expires = strtotime($session->expires);
        if($expires < time()){
            UACookie::remove(['id'=>$session->id]);
            return null;
        }

        // session almost expires? increase it
        $next_expires = time() + ($cookie_expires/7);
        if($expires < $next_expires){
            $next_expires = time() + $cookie_expires;
            $session_set = [
                'expires' => date('Y-m-d H:i:s', $next_expires)
            ];
            UACookie::set($session_set, ['id'=>$session->id]);
            $session->expires = $session_set['expires'];

            \Mim::$app->res->addCookie($cookie_name, $session->hash, $cookie_expires);
        }

        self::$session = $session;
        return (string)$session->user;
    }

    static function loginById(string $identity): ?array{
        $config = \Mim::$app->config->libUserAuthCookie;
        $cookie_name = $config->cookie;
        $cookie_expires = $config->expires;

        $result = [
            'name' => $cookie_name,
            'expires' => $cookie_expires,
            'token' => null
        ];

        while(true){
            $hash = base64_encode(password_hash(uniqid().'.'.uniqid(), PASSWORD_DEFAULT));
            $hash = strrev($hash);
            $hash = trim($hash, '=');

            if(UACookie::getOne(['hash'=>$hash]))
                continue;

            $result['token'] = $hash;
            break;
        }

        \Mim::$app->res->addCookie($cookie_name, $result['token'], $cookie_expires);

        UACookie::create([
            'user' => $identity,
            'hash' => $result['token'],
            'expires' => date('Y-m-d H:i:s', (time()+$cookie_expires))
        ]);

        return $result;
    }

    static function logout(): void{
        if(!self::$session)
            return;
        
        UACookie::remove(['id'=>self::$session->id]);

        $config = \Mim::$app->config->libUserAuthCookie;
        $cookie_name = $config->cookie;

        \Mim::$app->res->addCookie($cookie_name, '', -1000);
    }
}