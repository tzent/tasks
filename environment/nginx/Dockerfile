FROM nginx:1.19.8-alpine

ARG SERVICE

COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/$SERVICE/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/$SERVICE/ssl /etc/nginx/ssl

EXPOSE 443