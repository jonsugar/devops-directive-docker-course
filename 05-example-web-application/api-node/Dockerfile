# ------------------------------------------------------------------------------
# Built-on: 04-JUL-2023 18:12:30 UTC
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
FROM node:18-bookworm-slim

LABEL org.opencontainers.image.authors="jonathansugar@mac.com"
LABEL org.opencontainers.image.created="2023-07-04T19:12:+01:00"
LABEL org.opencontainers.image.licenses="SPDX-License-Identifier: MIT"
LABEL org.opencontainers.image.url="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/api-node"
LABEL org.opencontainers.image.documentation="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/api-node"
LABEL org.opencontainers.image.source="https://github.com/jonsugar/devops-directive-docker-course/tree/main/05-example-web-application/api-node"
LABEL org.opencontainers.image.version="1.0"
LABEL org.opencontainers.image.vendor="netcandy"
LABEL org.opencontainers.image.title="Demo node API"
LABEL org.opencontainers.image.description="Demo node API for Complete Docker Course - From BEGINNER to PRO! (Learn Containers)"

# Set NODE_ENV
ENV NODE_ENV production

# Specify working directory other than /
WORKDIR /usr/src/app

# Copy only files required to install
# dependencies (better layer caching)
COPY package*.json ./

# Install only production dependencies
# Use cache mount to speed up install of existing dependencies
RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm ci --only=production

# Use non-root user
# Use --chown on COPY commands to set file permissions
USER node

# Copy remaining source code AFTER installing dependencies.
# Again, copy only the necessary files
COPY --chown=node:node ./src/ .

# Indicate expected port
EXPOSE 3000

CMD ["node", "index.js"]
