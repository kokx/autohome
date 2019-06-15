FROM nginx:alpine
MAINTAINER  <pieter@kokx.org>

RUN apk add --update nodejs

COPY . /usr/src/autohome
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

RUN npm build

WORKDIR /usr/src/autohome
