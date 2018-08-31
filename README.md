# lib-user-auth-cookie

Module authorizer untuk module `lib-user`. Module ini mengambil data cookie untuk
mengidentifikasi user.

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install lib-user-auth-cookie
```

## Konfigurasi

Tambahkan konfigurasi seperti di bawah pada aplikasi untuk menset opsi cookie:

```php
return [
    'libUserAuthCookie' => [
        'cookie' => '_mu', // cookie name
        'expires' => 604800
    ]
];
```

## Penggunaan

Ketika service `user` di panggil, module ini akan langsung digunakan untuk mengidentifikasi
user yang sedang login.

Untuk menset user sebagai login, gunakan library `LibUserAuthCookie\Authorizer\Cookie` untuk
mendapatkan dan menset cookie:

```php
use LibUserAuthCookie\Authorizer\Cookie;

$cookie = Cookie::loginById($user_id);
// $cookie = [
//  'name' => '_mu',
//  'expires' => 60480,
//  'token' => 'random-string'
// ];
```

Nilai yang dikembalikan oleh method `loginById` hanya sebagai referensi, cookie akan langsung
di set oleh method ini dan akan diteruskan ke browser jika pada akhir eksekusi aplikasi menjalankan
perintah `$this->res->send()`.