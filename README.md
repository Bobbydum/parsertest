# Parser

Универсальный парсер.

# Зависимости
+ Php > 7.0
+ установленый <https://getcomposer.org>
+ установленный rabbitMq <https://www.rabbitmq.com/>

# Конфигурация
./config/config.php
+ upload - пака для заливки файлов
+ format - таблица форматов которые поддерживает парсер (для удобства добавления)
* amqp - параметры сервера очередей (в данный момент реализовано использование AMQP протокола)
+ host - хост на котором находится сервер очередей
+ port - порт
+ user - пользователь
+ password - пароль
+ vhost по умолчанию "/"
+ amqp_debug - скрывать или показывать дебаг вывод при паблише и считывании очередей
+ parse_queue - название очереди
* log - папка для записи логов
*db_connect - параметры конекшена к базе
+ db.options
+ driver сейчас прописан pdo_mysql
+ dbname - имя базы
+ host - хост
+ user - пользователь для подключения к базе
+ password - пароль для подключения к базе

Внимание перед работой необходимо развернуть базу с файла  [parser.sql]
# Список таблиц
+ `products` - таблица в которой хранятся данные об импортированых продуктах
+ `users` - пользователи у которых мы импортируем продукты
+ `values_relation` - таблица соответствий названия поля в нашей таблице `products` и файле пользователя
+ `relation` - таблица связи ключей продуктов в нашей таблице и в файлах пользователей (для того чтоб можно было обновлять продукт)
+ `currency_relation` - таблица соответствия значения валюты в файле пользователя с тем который будет сохранятся у нас (с указаним дефолтного значения если оно не указано для товара пользователя)
+ `state_relation` - - таблица соответствия значения состояния продукта в файле пользователя с тем который будет сохранятся у нас (с указаним дефолтного значения если оно не указано для товара пользователя)
> Последние две таблицы это пример создания таблиц соответствия в том случае если нам нужно получить фиксированые для нас значения полей а у разных пользователей они разные. При создании новых таблиц их также нужно добавлять в модель Products.php
# Запускающие файлы
+ php console.php publisher - команда которую мы прописываем в крон и который читает состояние пользователя и стягивает файл и ссылки указаной в поле `url_for_parse`  таблички `users` с переодичнойстью которая указана в поле `update_interval` в минутах
+ php console.php consumer  - команда которая запускается в кроне вида * * * * * php /absolute/path/to/file или сервисом типа supervisord для того чтоб принимать данные из очереди парсить и складывать в таблицу `products`
# Aдминка
>пока не реализована изменять данные для парсинга файлов нового вида и добавления пользователей пока можно через UI mysql

