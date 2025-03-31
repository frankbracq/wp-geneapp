# WP GeneApp

Un plugin WordPress pour intégrer la web app genealogie.app via iframe, avec transmission sécurisée de l'utilisateur WordPress.

## Fonctionnalités

- Intégration via iframe : `[geneapp_embed src="https://genealogie.app" auto_height="true"]`
- Données utilisateur transmises : ID, email, timestamp
- Signature HMAC vérifiable côté app intégrée
- Interface admin pour configurer la clé HMAC
- Redimensionnement automatique de l’iframe (`auto_height="true"`)

## Installation

1. Téléversez le dossier `wp-geneapp` dans `wp-content/plugins`
2. Activez le plugin dans le tableau de bord WordPress
3. Configurez votre clé HMAC via **Réglages > WP GeneApp**
4. Ajoutez le shortcode dans votre page :

```plaintext
[geneapp_embed src="https://app.genealogie.app" auto_height="true"]
