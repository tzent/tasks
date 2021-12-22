FROM pantsel/konga:next

ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it

ENTRYPOINT wait-for-it konga-mysql:3306 -- node /app/bin/konga.js prepare && /app/start.sh
