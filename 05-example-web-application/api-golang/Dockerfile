# Pin specific version for stability
# Use separate stage for building image
# Use debian for easier build utilities
FROM golang:1.19-bullseye AS build

WORKDIR /app

# Add non root user
RUN useradd -u 1001 nonroot

# Copy only files required to install dependencies (better layer caching)
COPY go.mod go.sum ./

# Use cache mount to speed up install of existing dependencies
RUN --mount=type=cache,target=/go/pkg/mod \
  --mount=type=cache,target=/root/.cache/go-build \
  go mod download

COPY . .

# Compile application during build rather than at runtime
# Add flags to statically link binary
RUN go build \
  -ldflags="-linkmode external -extldflags -static" \
  -tags netgo \
  -o api-golang

# Use separate stage for deployable image
FROM scratch

LABEL org.opencontainers.image.authors="jonathansugar@mac.com"
LABEL org.opencontainers.image.created="2023-07-03T14:06"
LABEL org.opencontainers.image.licenses="SPDX-License-Identifier: MIT"
LABEL org.opencontainers.image.url="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.documentation="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.source="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.version="0.1"
LABEL org.opencontainers.image.vendor="netcandy"
LABEL org.opencontainers.image.title="Demo React client"
LABEL org.opencontainers.image.description="Demo React client for Complete Docker Course - From BEGINNER to PRO! (Learn Containers)"

# Set gin mode
ENV GIN_MODE=release

WORKDIR /

# Copy the binary from the build stage
COPY --from=build /app/api-golang api-golang

# Indicate expected port
EXPOSE 8080

# Copy the binary from the build stage
COPY --from=build /app/api-golang api-golang

CMD ["/api-golang"]
