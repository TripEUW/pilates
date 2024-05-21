# Usar la imagen base de Ubuntu
FROM ubuntu:20.04

# Establecer el entorno de no interactivo para evitar prompts durante la instalación
ENV DEBIAN_FRONTEND=noninteractive

# Actualizar la lista de paquetes e instalar algunas utilidades básicas
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    git \
    zip \
    unzip \
    vim \
    nano \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update

# Instalar PHP 7.2 y las extensiones necesarias
RUN apt-get install -y \
    php7.2 \
    php7.2-cli \
    php7.2-fpm \
    php7.2-mysql \
    php7.2-xml \
    php7.2-mbstring \
    php7.2-curl \
    php7.2-zip \
    php7.2-json \
    php7.2-bcmath \
    php7.2-intl \
    php7.2-gd \
    php7.2-soap \
    php7.2-readline \
    mysql-client \
    && apt-get clean

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar el directorio de trabajo

# Instalar Laravel globalmente usando Composer

# Añadir las herramientas de Laravel al PATH
ENV PATH="$PATH:/root/.composer/vendor/bin"

# Exponer el puerto 8000 (usado por el servidor de desarrollo de Laravel)
EXPOSE 8000

# Comando para mantener el contenedor en ejecución
CMD ["php", "-a"]
