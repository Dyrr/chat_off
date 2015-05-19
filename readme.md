# chat_off 1.0 - Manuale

## VERSIONE STABILE

La versione corrente della chat è stabile e completamente funzionante anche se in alcuni punti deve essere ancora commentata e mancano una revisione della documentazione.

## COME INSTALLARE LA CHAT

1. Scaricare dalla release il file chat_off.zip.

2. Estrarre i file contenuti nell'archivio.

3. Aprire il file config.inc.php e impostare l'os utilizzato (il GDRCD 5.X è selezionato di default).
   Nel caso si volesse modificare qualche altra impostazione queste sono tutte nella sezione "impostazioni della chat"

4. Caricare sul server tutti i file e le directory precedentemente estratti mantenendo la struttura del pacchetto che è la seguente:

```
chat_off
│   .gitattributes
│   .gitignore
│   chatoff.php
│   post.php
│   readme.md
│   select_os.php
│   
├───application
│   ├───controller
│   │   └───classi
│   │       └───modules
│   │               chatOff.class.php
│   │               
│   └───viewer
│       ├───css
│       │       chatoff.css
│       │       
│       ├───img
│       │       bbcode.png
│       │       cross.png
│       │       
│       ├───js
│       │       bbcode_ui.js
│       │       chat_off.js
│       │       
│       └───template
├───documentation
│   └───html
│       └───search
└───log
```

La cartella documentation contiene solamente la documentazione generata dai commenti al codice e potrà essere eliminata in qualsiasi momento senza compromettere il funzionamento del pacchetto.