# WP GeneApp

[![Latest Release](https://img.shields.io/github/v/release/frankbracq/wp-geneapp?label=Latest%20Release)](https://github.com/frankbracq/wp-geneapp/releases/latest)
[![Build](https://github.com/frankbracq/wp-geneapp/actions/workflows/release.yml/badge.svg)](https://github.com/frankbracq/wp-geneapp/actions)
[![Download Plugin](https://img.shields.io/github/downloads/frankbracq/wp-geneapp/total?label=Download%20Plugin)](https://github.com/frankbracq/wp-geneapp/releases/latest)

Un plugin WordPress pour intégrer genealogie.app via iframe dans votre site internet.

---

## ✨ Fonctionnalités

- ✅ Intégration via iframe : `[geneapp_embed src="https://app.genealogie.app" auto_height="true"]`
- ✅ Transmission sécurisée de l’utilisateur WordPress : ID, email, timestamp
- ✅ Signature HMAC vérifiable côté app intégrée
- ✅ Interface admin WordPress pour gérer la clé HMAC
- ✅ Redimensionnement automatique de l’iframe (`auto_height="true"`)

---

## 🛠 Installation

1. [Téléchargez la dernière version ici](https://github.com/frankbracq/wp-geneapp/releases/latest)
2. Téléversez le fichier `.zip` dans `Extensions > Ajouter > Téléverser une extension`
3. Activez le plugin
4. Configurez votre clé HMAC dans **Réglages > WP GeneApp**
5. Intégrez votre app avec le shortcode :

```plaintext
[geneapp_embed src="https://app.genealogie.app" auto_height="true"]
```

---

## 📜 Licence

Ce plugin est distribué sous licence [GPL v2 ou ultérieure](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
