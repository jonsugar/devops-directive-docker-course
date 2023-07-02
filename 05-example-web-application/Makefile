DATABASE_URL:=postgres://postgres:foobarbaz@localhost:5432/postgres

objects = node_modules run-npm-install-api-node run-npm-install-client-react \
	make-go-workspace

.PHONY: run-postgres
run-postgres:
	@echo Starting postgres container
	-docker run \
		--rm \
		-e POSTGRES_PASSWORD=foobarbaz \
		-v pgdata:/var/lib/postgresql/data \
		-p 5432:5432 \
		postgres:15.1-alpine

run-npm-update-api-node:
	@echo Updating node dependencies
	cd api-node && npm update

run-npm-install-api-node:
	@echo Installing node dependencies
	cd api-node && npm install
	touch run-npm-install-api-node

.PHONY: run-api-node
run-api-node: run-npm-install-api-node run-npm-update-api-node
	@echo Starting node api
	cd api-node && \
		DATABASE_URL=${DATABASE_URL} \
		npm run dev

.PHONY: run-api-golang
run-api-golang: make-go-workspace
	@echo Starting golang api
	cd api-golang && \
		export GOPATH=${PWD}/go-workspace && \
		DATABASE_URL=${DATABASE_URL} go run main.go

make-go-workspace:
	@echo creating go workspace
	export GOPATH=${PWD}/go-workspace && \
	cd api-golang && go mod download
	touch make-go-workspace

run-npm-update-client-react:
	@echo Updating node dependencies
	cd client-react && npm update

run-npm-install-client-react:
	@echo Installing node dependencies
	cd client-react && npm install
	touch run-npm-install-client-react

.PHONY: run-client-react
run-client-react: run-npm-install-client-react run-npm-update-client-react
	@echo Starting react client
	cd client-react && \
		npm run dev

.PHONY: clean
clean:
	@echo cleaning build artifacts
	rm -rf $(objects)
