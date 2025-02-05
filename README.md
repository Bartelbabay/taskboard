Clone the repo

run `composer install`<br>
run `npm install`<br>
rename `.env.example` to `.env` and configure your database, google and telegram parameters (or use mine)<br>
get credentials.json from your google cloud console https://developers.google.com/workspace/guides/create-credentials?hl=ru#service-account<br>
copy `credentials.json` to `storage/app/credentials.json`<br>
grant editor access for created service account to the spreadsheet you want to store tasks<br>
run `php artisan migrate`<br>
run `npm run build`<br>
run `php artisan tinker`<br>
then execute `DB::table('users')->insert(['username'=>'admin','name'=>'MyUsername','email'=>'thisis@myemail.com','password'=>Hash::make('admin')])
` now you can login into application using admin:admin credentials
configure your webserver the public directory of project<br>
enjoy...
Link to the TG group to check https://t.me/NickolayLantinovTaskboardTest
