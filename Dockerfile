FROM ubuntu:latest

ENV TZ="America/New_York"
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get --yes update && \
    apt-get --yes upgrade && \
    apt-get --yes install software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get --yes install apache2 php8.0 tzdata git unzip php8.0-xml wget curl nano php8.0-intl php8.0-mysql php8.0-yaml

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    chmod +x ./composer.phar && \
    mv composer.phar /usr/bin/composer 

