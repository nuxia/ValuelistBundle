## TODO

### Taches

- Ajouter value dans admin
- Remettre value obligatoire dans la base
- Mettre en place l'optimisation de `Stof`(dans la configuration de finaho)
- Mise à jour des test unitaires
- Mise à jour de la documentation
- Revoir interface findByCriteria et findStmtByCriteria
- Système de strategy pour la génération des références de valuelist
- logs au chargement des fixtures

### Debug 

- Debug quand on désactive l'administration (selon Stof il faut check dans la configuration de Doctrine ou FrameworkBundle ils le font)

### Evolution

Donner la possibilité de récupérer des `valuelist` globales + parent

Exemple sur GET :

```
	Global: CPA / REC / EVF
	ESL : ADH
	GET: RTT
```
