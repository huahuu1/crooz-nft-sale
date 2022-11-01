#!/bin/sh

# start crontab
crond -l 1 -f &

# start supervisor
supervisord -c /etc/supervisord.conf &

# Wait for any process to exit
wait -n

# Exit with status of process that exited first
exit $?
