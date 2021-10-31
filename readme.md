# MVC
Pola arsitektur pada perancangan perangkat lunak berorientasi objek dengan tujuan untuk memisahkan antara **tampilan**, **data** dan **proses**.

## Pengertian MVC
### Model
- Representasi Pengetahuan
- Mengelola Data
- Logika Bisnis
### View
- Output apa yang akan dilihat oleh user
- Representasi Visual dari model
- Lapisan Presentasi
### Controller
- Perantara antara model dan view
- Menangani pemrosesan pada aplikasi
- Menangani aksi dari user

## Kenapa harus pakai MVC
- Organisasi dan struktur code
- Pemisah logic dan tampilan
- Perawatan Kode
- Implementasi Konsep OOP
- Digunakan banyak framework

## Apa yang dibuat pada projek ini

### Struktur Folder
- public : Folder yang akan diakses oleh user
- app : Folder yang menyimpan file **MVC**
- core : yang akan mengelola inti dari mvc

```bash
	.
	├── app
	│  ├── controllers
	│  ├── core
	├── ...
	│  ├── models
	│  ├── views
	│	
	├── public
	│  ├── css
	│  ├── js
	│  ├── img

```

