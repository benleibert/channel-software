#!/bin/sh

mysqld_safe --user=mysql &

service apache2 start

while true
do
    sleep 1;
done