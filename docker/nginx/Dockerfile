FROM nginx:alpine
MAINTAINER  <pieter@kokx.org>

RUN apk add --update nodejs nodejs-npm

COPY . /usr/src/autohome
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /usr/src/autohome

RUN npm install
RUN npm run build
