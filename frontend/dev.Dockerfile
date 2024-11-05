FROM node:22.11.0-alpine3.20

WORKDIR /usr/app

COPY ./frontend/package*.json ./

RUN npm install

COPY ./frontend ./

EXPOSE 3000

USER node

CMD [ "npm", "run", "dev" ]


