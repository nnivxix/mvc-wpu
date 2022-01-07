# PHP MVC - PZN

![mvc php](imgs/mvc.png)

## Routing

*Best practice* dari menggunakan MVC ialah tidak menggunakan file secara langsung melainkan menggunakan *routing*, di PHP ada value array dari `$SERVER['PATH_INFO']` yang mana bisa mengetahui letak dimana kita mengakses halaman kita berada.

## Middleware

Middleware merupakan bagian kode yang dieksekusi sebelum `Controller` dieksekusi, contohnya seperti pengecekan apakah pengguna sudah login atau belum.

## Local domain

Terkadang kita bosan dengan nama yang kita akses dengan nama `localhost`, tetapi kita bisas mengganti namanya dengan nama domain yang kita inginkan

|OS|Directory|
|---|-------|
|Mac atau Linux| `:/etc/hosts`|
|Windows | `C:\Windows\System32\drivers\etc`|

Tambahkan di file `hosts`

```plain text
127.0.0.1 hana.sa
```

Untuk mengaksesnya jangan menggunakan ~~localhost~~ tapi dengan `127.0.0.1`

`php -S 127.0.0.1:8989`

## HTTPD

Sebelum menggunakan virtualhost aktipkan dahulu jika belum aktif virtual hostnya di `C:\xampp\apache\conf\httpd.conf` kemudian cari :

```bash
# Virtual hosts
Include conf/extra/httpd-vhosts.conf
```

Kemudian maju ke lokasi config di windows `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

```bash
<VirtualHost *:80>
    ServerAdmin admin@hanasa-mvc.test
    DocumentRoot "C:/xampp/htdocs/hansoflast/pzn-mvc-php/public"
    ServerName hanasa-mvc.test
    ErrorLog "logs/hanasa-mvc.test-error.log"
    CustomLog "logs/hanasa-mvc.test-access.log" common
</VirtualHost>
```

Kemudian Buka file host di `C:\Windows\System32\drivers\etc`

```plain text
127.0.0.1 hanasa-mvc.test
```

Namun itu semua mempunyai prilaku yang berbeda ketika menjalankan php seperti biasa dan mempunyai configurasi di `.htaccess` dan silakan buat configurasinya dari [repo CodeIgniter 4](https://github.com/codeigniter4/CodeIgniter4/blob/v4.1.3/public/.htaccess).
