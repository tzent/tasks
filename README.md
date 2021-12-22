RabbitMQ UI - http://localhost:15672 (admin/password)
Konga UI - http://localhost:1337 (трябва регистрация, линк за връзка с Kong - http://kong-gateway:8001 )
MySQL remote connection localhost:25432 

docker exec -it identity bin/console doctrine:fixtures:load (добавя тестови потребители)

