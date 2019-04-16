!/bin/bash
echo "Устанавливаем ссылки конфига"
ln -sf /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony
echo "Запускаем сервак"
nginx