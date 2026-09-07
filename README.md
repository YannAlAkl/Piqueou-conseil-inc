# Portail PIQUÉOU Conseil Inc.
 
Portail de conformité en cybersécurité (CMMC, Loi 25, ISO 27001) développé avec Laravel 13.
 
Trois espaces : **client** (répond au questionnaire), **analyste** (rédige les recommandations), **administrateur** (gère les comptes, les questionnaires et l'infolettre).
 
## Prérequis
 
- PHP 8.4 ou plus   
- Composer
 
## Installation
 
```bash
cd PFE-Backend
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```
 
> Sous macOS/Linux, remplacer `copy` par `cp`.
 
L'application est accessible sur **http://localhost:8000**
 
Aucune base de données à créer : le projet utilise SQLite.
 
## Compte administrateur
 
| Courriel | Mot de passe |
|---|---|
| admin@test.com | password123 |
## Site vitrine
 
```bash
cd showcase-site
php -S localhost:8080
```
 
Accessible sur **http://localhost:8080**
 
## En cas de problème
 
```bash
php artisan config:clear
php artisan view: clear