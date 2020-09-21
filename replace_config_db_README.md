1) С сайта https://downloadsapachefriends.global.ssl.fastly.net/7.4.10/xampp-windows-x64-7.4.10-0-VC15-installer.exe?from_af=true скачиваем и устанавливаем XAMPP по такому пути: C:\xampp;
2) Скачать по ссылке https://cloud.mail.ru/public/5bfR%2F5s234kK49 архив replace_config_db.zip;
3) Данный архив нужно разархивировать в корень диска С (С:\) с заменой всех запрашиваемых файлов;
4) В файле .env.exzample есть настройки по умолчанию для соединения с БД WhiteTumbler. Нужно переименовать его из .env.exzample в .env;
5) Запускаем xampp, запускаем apache (start). По урлу http://127.0.0.1/phpPgAdmin/ откроется phpPgAdmin;
6) Для входа на сервер PostgreSQL нужно использовать user = root, password = root;