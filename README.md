# Quiz Master

Quiz Master est une application web de gestion et de création de quiz, développée avec **Laravel 10** et **Filament**. Elle permet aux utilisateurs de créer, gérer, jouer et analyser des quiz interactifs, avec un système d'administration complet, des statistiques, des paiements, et une gestion avancée des utilisateurs.

## Fonctionnalités principales

- **Gestion des quiz** : création, édition, suppression, activation/désactivation.
- **Questions et réponses** : support des questions à choix multiples, réponses multiples, etc.
- **Participation aux quiz** : interface utilisateur dédiée pour jouer aux quiz.
- **Statistiques et rapports** : suivi des vues, participations, temps moyen, scores, classement.
- **Gestion des utilisateurs** : rôles, permissions, profils, abonnements.
- **Paiements** : intégration Stripe, Paypal, Razorpay.
- **Support multilingue** : plusieurs langues disponibles.
- **Notifications et emails** : gestion des notifications et envois automatiques.
- **Tableau de bord Filament** : interface d'administration moderne et personnalisable.

## Structure du projet

- `app/Models/` : Modèles principaux (`Quiz`, `Question`, `Answer`, `UserQuiz`, etc.)
- `app/Filament/User/Resources/` : Ressources Filament pour la gestion côté utilisateur.
- `resources/views/filament/user/resources/quizzes-resource/pages/` : Vues personnalisées pour les pages de quiz.
- `database/migrations/` : Migrations pour la structure de la base de données.
- `public/images/` : Images et assets utilisés dans l'application.

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Donaldmbajouen/test_everblue.git
   cd quiz-master
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configurer l'environnement**
   - Copier `.env.example` en `.env` et adapter les variables (base de données, mail, services externes, etc.)
   - Générer la clé d'application :
     ```bash
     php artisan key:generate
     ```

4. **Migrer la base de données et a[[liquer les seeders pour pre-remplir les donnees de bases**
   ```bash
   php artisan migrate --seed
   ```

5. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

6. **Accéder à l'application**
   - Frontend : [http://localhost:8000](http://localhost:8000)
   - Panel utilisateur : [http://localhost:8000/user/quizzes](http://localhost:8000/user/quizzes)

## Personnalisation

- Les widgets, pages et ressources Filament sont personnalisables dans `app/Filament/User/Resources/QuizzesResource/`.
- Les vues Blade sont dans `resources/views/filament/user/resources/quizzes-resource/pages/`.

## Contribution

Les contributions sont les bienvenues ! Merci de soumettre vos PR ou suggestions via GitHub.

