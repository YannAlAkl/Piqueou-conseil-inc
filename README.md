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
 
C'est le seul compte créé à l'installation. Les questionnaires, eux, sont déjà en place.
 
## Premier parcours (10 minutes)
 
**1. Créer un analyste**
Connectez-vous en administrateur, puis *Utilisateurs → Analystes → Nouvel analyste*.
Son mot de passe est généré automatiquement et envoyé par courriel — voir la section « Courriels » ci-dessous pour le récupérer.
 
**2. Créer un client**
*Utilisateurs → Clients → Nouveau client*, en choisissant le statut **Actif**.
Un courriel de vérification est envoyé : récupérez le lien dans le journal et ouvrez-le pour activer l'accès.
 
**3. Remplir un questionnaire**
Connectez-vous avec le compte client, ouvrez un questionnaire, répondez et cliquez sur **Envoyer**.
 
**4. Assigner puis analyser**
En administrateur, *Questionnaires → Questionnaires envoyés* : ouvrez le dossier et assignez-le à l'analyste.
Connectez-vous ensuite en analyste, rédigez les recommandations et la conclusion, puis **Envoyer l'analyse**.
 
Le client voit alors les recommandations sous chacune de ses réponses.
 
## Courriels
 
Les courriels ne sont pas réellement expédiés. Ils sont écrits dans :
 
```
PFE-Backend/storage/logs/laravel.log
```
 
C'est là que se trouvent le **mot de passe généré de l'analyste** et le **lien de vérification du client**. Ouvrez le fichier et allez à la fin.
 
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