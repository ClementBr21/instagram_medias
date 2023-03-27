<h1>Instagram Médias</h1>

![PHP version 8.2](https://img.shields.io/badge/php-8.2-blue?logo=php)
![Laravel 10](https://img.shields.io/badge/Laravel-10-brightgreen?logo=laravel)

## Objectifs
Ce projet a pour but de récupérer les médias d'une page instagram et de les afficher.

## Installation projet

```shell
cp .env.example .env
# Remplir le fichier .env (principalement APP_* / INSTAGRAM_* / PUSHER_* / BROADCAST_DRIVER)
php artisan key:generate
composer install
npm install
npm run dev
```

## Étapes du projet

1. Authentification API Instagram
    - Récupération autorisation compte utilisateur
    - Récupération jeton d'autorisation courte durée à partir de ce code
    - Récupération jeton d'autorisation longue durée à partir du jeton de courte durée
2. Récupération des posts (medias) Instagram avec système de pagination
3. Ajout d'une commande et de Websocket afin de récupérer les nouveaux posts et informer l'utilisateur que de nouveaux médias sont disponibles

## Résultat projet
### Étapes 1 & 2
[![Authentification + Récupération](https://img.youtube.com/vi/K8lOyclWgY0/0.jpg)](https://youtu.be/K8lOyclWgY0)

### Étapes 3
[![Websocket](https://img.youtube.com/vi/qZJkopwUFZg/0.jpg)](https://youtu.be/qZJkopwUFZg)

