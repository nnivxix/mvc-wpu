# PHP MVC - PZN

![mvc php](imgs/mvc.png)

## Routing

*Best practice* dari menggunakan MVC ialah tidak menggunakan file secara langsung melainkan menggunakan *routing*, di PHP ada value array dari `$SERVER['PATH_INFO']` yang mana bisa mengetahui letak dimana kita mengakses halaman kita berada.

## Middleware

Middleware merupakan bagian kode yang dieksekusi sebelum `Controller` dieksekusi, contohnya seperti pengecekan apakah pengguna sudah login atau belum.
