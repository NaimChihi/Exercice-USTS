#syntax=docker/dockerfile:1

# Utilisation de l'image de base FrankenPHP
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream

# Étape de construction de l'image de base
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

# Définition d'un volume pour stocker les données persistantes
VOLUME /app/var/

# Installation des dépendances nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    acl \
    file \
    gettext \
    git \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP requises
RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        zip \
    ;

# Configuration de Composer pour permettre l'exécution en tant que superutilisateur
ENV COMPOSER_ALLOW_SUPERUSER=1

# Définition du répertoire pour la configuration de PHP
ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

# Installation de l'extension PDO pour MySQL (nécessaire pour Doctrine)
RUN install-php-extensions pdo_mysql

# Copie des fichiers de configuration
COPY --link frankenphp/conf.d/10-app.ini $PHP_INI_DIR/app.conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

# Définition du point d'entrée du conteneur
ENTRYPOINT ["docker-entrypoint"]

# Définition de l'état de santé du conteneur
HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

# Commande par défaut pour démarrer l'application
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

# Étape de développement
FROM frankenphp_base AS frankenphp_dev

# Définition de l'environnement de développement
ENV APP_ENV=dev XDEBUG_MODE=off

# Utilisation du fichier de configuration de développement
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Installation de l'extension Xdebug pour le débogage
RUN set -eux; \
    install-php-extensions xdebug;

# Copie de la configuration de développement
COPY --link frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

# Commande par défaut pour le mode développement
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

# Étape de production
FROM frankenphp_base AS frankenphp_prod

# Définition de l'environnement de production
ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

# Utilisation du fichier de configuration de production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copie de la configuration de production
COPY --link frankenphp/conf.d/20-app.prod.ini $PHP_INI_DIR/app.conf.d/
COPY --link frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

# Préparation de l'installation de Composer
COPY --link composer.* symfony.* ./
RUN set -eux; \
    composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress;

# Copie des sources de l'application
COPY --link . ./
RUN rm -Rf frankenphp/

# Exécution des commandes pour finaliser la configuration
RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer dump-env prod; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync;
