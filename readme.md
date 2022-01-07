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
