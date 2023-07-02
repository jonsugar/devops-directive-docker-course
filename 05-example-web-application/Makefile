DATABASE_URL:=postgres://postgres:foobarbaz@localhost:5432/postgres

objects = node_modules run-npm-install

.PHONY: run-postgres
run-postgres:
	@echo Starting postgres container
	-docker run \
		--rm \
		-e POSTGRES_PASSWORD=foobarbaz \
		-v pgdata:/var/lib/postgresql/data \
		-p 5432:5432 \
		postgres:15.1-alpine

.PHONY: run-npm-update
run-npm-update:
	@echo Updating node dependencies
	cd api-node && npm update

run-npm-install:
	@echo Installing node dependencies
	cd api-node && npm install
	touch run-npm-install

.PHONY: run-api-node
run-api-node: run-npm-install run-npm-update
	@echo Starting node api
	cd api-node && \
		DATABASE_URL=${DATABASE_URL} \
		npm run dev

.PHONY: run-api-golang
run-api-golang:
	@echo Starting golang api
	cd api-golang && \
		DATABASE_URL=${DATABASE_URL} \
		go run main.go

.PHONY: run-client-react
run-client-react:
	@echo Starting react client
	cd client-react && \
		npm run dev

.PHONY: clean
clean:
	@echo cleaning build artifacts
	rm -rf $(objects)