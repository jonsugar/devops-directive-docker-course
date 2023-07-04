# ------------------------------------------------------------------------------
# Built-on: 04-JUL-2023 18:24:30 UTC
# Version:  1.0
# ------------------------------------------------------------------------------
#
#  At build time, the following applied:
#
#    Operating System:
#      debian stable
#      version 12 (bookworm)
#
#    Node version: Current LTS
#      version: 18.16.1
#
# ------------------------------------------------------------------------------
FROM node:18-bookworm-slim  AS build

# Specify working directory other than /
WORKDIR /usr/src/app

# Copy only files required to install
# dependencies (better layer caching)
COPY package*.json ./

# Use cache mount to speed up install of existing dependencies
RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm install

COPY . .

RUN npm run build

# Use separate stage for deployable image
FROM nginxinc/nginx-unprivileged:1.25-alpine-perl

LABEL org.opencontainers.image.authors="jonathansugar@mac.com"
LABEL org.opencontainers.image.created="2023-07-04T19:24:04+01:00"
LABEL org.opencontainers.image.licenses="SPDX-License-Identifier: MIT"
LABEL org.opencontainers.image.url="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.documentation="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.source="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/client-react"
LABEL org.opencontainers.image.version="1.0"
LABEL org.opencontainers.image.vendor="netcandy"
LABEL org.opencontainers.image.title="Demo React client"
LABEL org.opencontainers.image.description="Demo React client for Complete Docker Course - From BEGINNER to PRO! (Learn Containers)"

# Use COPY --link to avoid breaking cache if we change the second stage base image
COPY --link nginx.conf /etc/nginx/conf.d/default.conf

COPY --link --from=build usr/src/app/dist/ /usr/share/nginx/html

EXPOSE 8080