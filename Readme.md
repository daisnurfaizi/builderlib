# Bubuilder

Bubuilder adalah library untuk membuat entity dan builder pattern berdasarkan model database di Laravel.

## Instalasi

Anda dapat menginstal library ini melalui Composer. Jalankan perintah berikut:

```bash
composer require daisnurfaizi/bubuilder
```

## Penggunaan

anda dapat menggunakan library ini dengan cara sebagai berikut:

`php artisan create:entity nama-model`

untuk membuat entity

`php artisan create:builder nama-entity`

Untuk membuat builder berdasarkan entity

contoh:

`php artisan create:entity User`

`php artisan create:builder UserEntity`

Di dalam folder app/Http/Entity dan app/Http/Builder akan terbuat file UserEntity.php dan UserEntityBuilder.php

yang dapat anda gunakan untuk membuat builder pattern.

dengan contoh pengguaan sebagai berikut:

<!-- script php -->

`$user = new UserEntityBuilder();`

`$user->setEmail('JhonDoe');`

`$user->build();`

Atau anda dapat menggunakan Chaining method anda dapat menggunakan builder pattern dengan cara sebagai berikut
:

<!-- script php -->

`$user = (new UserEntityBuilder())->setEmail('JhonDoe')->setName('jhondoe')->build();`
