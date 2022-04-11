de := docker exec -it 
sy := $(de) php74-container php bin/console

up:
	docker-compose up -d --build

enter:
	$(de) php74-container /bin/sh

down:
	docker-compose down --remove-orphans

reset: 
	$(sy) doctrine:database:drop --force -q
	$(sy) doctrine:database:create -q
	$(sy) doctrine:migrations:migrate -q
	$(sy) doctrine:schema:validate -q

consume: 
	$(sy) messenger:consume async -vv

install:
	docker-compose up -d --build
	$(de) php74-container composer update -n
	$(sy) doctrine:migrations:migrate -q
	$(sy) doctrine:schema:validate -q

	
	