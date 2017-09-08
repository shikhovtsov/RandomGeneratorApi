random_generator_api
====================

Инструкция по установке:

1. Создать БД для проекта

2. Дать права на папки
sudo chmod -R 777 var/cache
sudo chmod -R 777 var/logs
sudo chmod -R 777 var/sessions

3. Composer install
После установки пакетов ввести данные о БД. Остальные поля оставить по дефолту

4. Сгенерировать таблицы в БД
php bin/console doc:sch:update --force

5. Запустить сервер
php bin/console server:start

6. Дальнейшие запросы можно совершать по адресу который появился в консоле 
([OK] Server listening on http://127.0.0.1:8000)

7. POST Метод http://127.0.0.1:8000/api/generate поддерживает поля min/max, которые отвечают за размер числа. Если их не задать, число будет варироваться от 0 до 100000

8. GET Метод http://127.0.0.1:8000/api/retrieve/{id} возвращает сгенерированное число
