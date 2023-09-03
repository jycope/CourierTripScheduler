FROM mysql:latest

COPY ./mysqldocker/my.cnf /etc/mysql/conf.d/my.cnf

ENV MYSQL_USER=dmitry
ENV MYSQL_PASSWORD=laker2288
ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=dbnew

COPY ./init.sql /docker-entrypoint-initdb.d/