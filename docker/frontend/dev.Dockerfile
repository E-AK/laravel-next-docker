FROM node:22.11.0-alpine3.20

WORKDIR /app
COPY ./frontend .
RUN yarn
EXPOSE 3000
ENTRYPOINT ["yarn", "dev"]
