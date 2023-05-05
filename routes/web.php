<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

Route::get('/', function () {
    $numberOfAccounts = 5000;
    $groupLengthMin = 10;
    $groupLengthMax = 50;
    $password = '123456';
    $accounts = [];

    for ($i = 0; $i < $numberOfAccounts; $i++) {
        $lastName = mb_convert_encoding(implode('', array_map(function () {
            return chr(mt_rand(0x81, 0x9F)) . chr(mt_rand(0x40, 0x7E));
        }, range(1, 2))), 'UTF-8', 'SJIS-win');

        $firstName = mb_convert_encoding(implode('', array_map(function () {
            return chr(mt_rand(0x81, 0x9F)) . chr(mt_rand(0x40, 0x7E));
        }, range(1, 2))), 'UTF-8', 'SJIS-win');

        $name = $lastName . ' ' . $firstName;
        $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . '@gmail.com';
        $group = Str::random(random_int($groupLengthMin, $groupLengthMax));

        $accounts[] = [
            'name' => $name,
            'email' => $email,
            'group' => $group,
        ];
    }

    $csv = Writer::createFromString('');
    $csv->insertOne(['Name', 'Email', 'Group']);

    foreach ($accounts as $account) {
        $csv->insertOne($account);
    }

    Storage::put('accounts.csv', $csv->getContent());
});
