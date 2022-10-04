FROM node:16

ENV NPM_CONFIG_LOGLEVEL info

RUN npm install -g cross-env

RUN npm install -g gulp
RUN npm install -g grunt-cli

WORKDIR /var/www

ENTRYPOINT npm install && npm run dev
