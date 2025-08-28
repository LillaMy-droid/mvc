<!--
---
author: amld24
written:
    "2025-04-14": 
---
-->
![Bild på programmering](./public/img/litenprogrammerare.png)

DV1608 V25 lp4 Objektorienterade webbteknologier
================================================

Denna kurs vid Blekinge Tekniska Högskola (BTH) fokuserar på att implementera objektorienterad PHP med MVC-struktur, med hjälp av ramverket Symfony och templating-motorn Twig. Kursen omfattar 6 kursmoment samt ett slutprojekt.

#### Klona kursrepo

För att klona kursrepo från GitHub, följ dessa steg:

1. Besök kursrepot på GitHub.
2. Klicka på `<> Code` och kopiera URL:en för GitHub CLI.
3. Öppna din terminal och kör följande kommando:
    ```
    git clone https://github.com/LillaMy-droid/mvc.git
    ```

#### Installera nödvändiga verktyg och paket

För att köra sajten lokalt, se till att du har följande installerat:

- **PHP 8.2+**: Den senaste versionen av PHP för att säkerställa kompatibilitet.
- **Symfony 7.2+**: Ett kraftfullt PHP-ramverk för att bygga robusta applikationer.
- **Composer**: Ett verktyg för att hantera PHP-paket och beroenden.
- **npm**: Node Package Manager för att hantera JavaScript-paket.

När du har dessa verktyg installerade, gå till roten av projektets mapp och kör följande kommandon för att installera alla nödvändiga paket:

```bash
composer install
npm install
```

För att starta servern och starta sidan kör du kommandot 
```bash
symfony server:start
```
### Scrutinizer Badges

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/badges/build.png?b=main)](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/build-status/main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/LillaMy-droid/mvc/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)