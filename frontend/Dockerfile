FROM node:22.11.0-alpine3.20

WORKDIR /usr/app

COPY ./frontend/package*.json ./

RUN npm install --production

COPY ./frontend ./

RUN npm run build

EXPOSE 3000

USER node

CMD [ "npm", "start" ]


