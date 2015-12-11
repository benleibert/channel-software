#
# Installs an instance of "Channel v2," intended for experimentation rather than production.
#

#Ubuntu based image
FROM phusion/baseimage:0.9.1

MAINTAINER Ben Leibert <ben.leibert@villagereach.org>

RUN apt-get update
RUN apt-get install lamp-server^ -y

ADD ChannelPackage/channel /var/www/channel/
ADD ChannelPackage/BaseSQL/channel_db.sql /var/www/channel/
ADD SystemConfig/startup.sh /var/www/channel/

RUN echo "mysqld_safe &" > /tmp/config \
    && echo "mysqladmin --silent --wait=30 ping || exit 1" >> /tmp/config \
    && echo "mysql < /var/www/channel/channel_db.sql" >> /tmp/config \
    && bash /tmp/config \
    && rm -f /tmp/config

EXPOSE 80

USER root
RUN chmod u+x /var/www/channel/startup.sh

CMD ["/var/www/channel/startup.sh"]

