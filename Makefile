init:
	docker-compose build --force-rm --na-cache
	make up

up:
	docker-compose up -d
	echo "Application is running at https://127.0.0.1:8030"

shema-update:
	docker exec -it guess /home/guess/bin/console doctrine:database:creata --if-not-exists
	docker exec -it guess /home/guess/bin/console doctrine:schema:update --force