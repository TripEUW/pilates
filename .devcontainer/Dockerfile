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
    php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-gd \
    php8.2-soap \
    php8.2-readline \
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
