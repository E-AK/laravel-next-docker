FROM node:22.11.0-alpine3.20

WORKDIR /app
COPY ./frontend/package.json ./frontend/yarn.lock ./
RUN yarn
COPY ./frontend .
EXPOSE 3000
ENTRYPOINT ["yarn", "dev"]