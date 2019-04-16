# symfony-chat

Небольшое приложение реализующее "чат в одной комнате". 

# Установка и запуск

```bash
$ git clone git@github.com:XaoZlo/symfony-chat.git
$ cd /symfony-chat
$ docker-compose up -d
$ docker-compose exec php bash
$ composer install
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
$ php bin/console doctrine:fixtures:load --no-interaction
```

# Использование

* Зайдите на [localhost](http://localhost)  
* Залогиньтесь под одним из пользователей:
```text
login: User_nickname
password: 1234

login: Nickname_username
password: 1234
```
* Напишите что-нибудь в чат!

# Алгоритм
Приложение работает с помощью четырёх контроллеров. 
AuthController проверяет есть ли совпадение по логину и паролю, если есть, то редирект на страницу с чатом.

ChatController отрисовывает страницу с чатом и добавляет сообщения в базу данных.
На странице чата создан iframe, в котором загружается страница /chat/window и обновляется один раз в секунду. 

ChatWindowController выбирает из базы сообщения и отрисовывает их на странице /chat/window.

ApiController возвращает json-строки.
Работу ApiController можно посмотреть на страницах:
 
[localhost/get/users](http://localhost/get/users) - Возвращает всех пользователей и их сообщения.

[localhost/get/messages](http://localhost/get/messages) - Возвращает все сообщения.

[localhost/get/user/{id}](http://localhost/get/user/1) - Возвращает информцию о данном пользователе и его сообщения.
