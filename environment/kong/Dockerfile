FROM kong:2.6.0-alpine
MAINTAINER Tzvetelin Tzvetkov "tzvetelin.tzvetkov@gmail.com"

USER root

ENV KONG_PLUGINS=jwt,acl

USER kong

CMD kong migrations bootstrap && \
    kong migrations up && \
    kong start
