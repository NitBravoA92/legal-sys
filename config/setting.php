<?php

/*
|--------------------------------------------------------------------------
| Company Information || SUPER ADMINISTRATOR SETTING
|--------------------------------------------------------------------------
|
| Those values are the main company information set by super administrator's user as setting data. These are used when the
| framework needs to place the user's settings in a notification or
| any other location as required by the application or its packages.
|
*/

return [
/*
|--------------------------------------------------------------------------
| System Name
|--------------------------------------------------------------------------
*/
'name' => env('APP_NAME'),

/*
|--------------------------------------------------------------------------
| System Owner
|--------------------------------------------------------------------------
*/
'owner' => env('APP_NAME'),

/*
|--------------------------------------------------------------------------
| Company Location
|--------------------------------------------------------------------------
*/
'address' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',

/*
|--------------------------------------------------------------------------
| Company Email
|--------------------------------------------------------------------------
*/
'email' => env('MAIL_FROM_ADDRESS'),

/*
|--------------------------------------------------------------------------
| Company Phone
|--------------------------------------------------------------------------
*/
'phone' => '+1 305 555 5555',

/*
|--------------------------------------------------------------------------
| System Logo
|--------------------------------------------------------------------------
*/
'logo' => env('APP_URL') . '/assets/img/logos/system-logo.png',

/*
|--------------------------------------------------------------------------
| About Company
|--------------------------------------------------------------------------
*/
'about_us' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe nam pariatur magni reiciendis, ex temporibus quidem, illum excepturi nesciunt harum sequi minus architecto eius, tempora consequuntur aliquam illo quo exercitationem!',

/*
|--------------------------------------------------------------------------
| Main System Language
|--------------------------------------------------------------------------
*/
'language' => 'es',

/*
|--------------------------------------------------------------------------
| Main copyright info
|--------------------------------------------------------------------------
*/
'copyright' => env('APP_NAME') . '. Todos los derechos reservados'

];