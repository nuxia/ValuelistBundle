### A spliter dans la documentatin

##Fixtures##

La définition des fixtures se fait dans un ou plusieurs fichiers `yml` situés dans `/DataFixtures/Valuelist` et ayant cette structure :

```yml
code-par-defaut:
    category: "category1"
    parent: "language-category-code" (facultatif)
    code: "code" (à definir ici si le code possede des espaces)
    value: "value"
```

Pour définir le libellé et la langue il y a deux possibilités :

Si la valuelist est présente dans plusieurs langues :

```yml
code-par-defaut:
	#category, code, parent, value
    labels: (facultatif)
        fr: "libelle fr"
        en: "libelle en"
```
Le nombre d'éléments dans `labels` définit le nombre de lignes qui sont insérées dans la table `valuelist`.

Sinon :

```yml
code-par-defaut:
	#category, code, parent, value
    label: "libelle"
    language: "langue" (default par defaut)
```

##Utilisation##

###PHP###

Pour utiliser les `valuelist` dans une classe php il faut injecter le service `nuxia_valuelist.manager`.

Pour récupérer un tableau de `valuelist` (ayant la structure `code => label`) on utilise la méthode `getValuelist()` du service prenant en paramètre la locale, la catégorie et le parent (facultatif).

Pour récupérer le libellé ou la valeur d'une `valuelist` on utilise la méthode `getValue()` du service prenant en paramètre la locale, la catégorie, le code et le parent (facultatif). Cette méthode renverra un tableau avec comme clés le libellé et la valeur (si elle est différente de null). Si la `valuelist` n'existe pas en base la méthode renverra renverra null.

Pour envoyer la locale, on peut utiliser la méthode `getLocale()` de l'objet `Request`. Il faut juste s'assurer qu'elle soit bien définie (dans le cas des `embed controller` notamment).

###Twig###

Dans les templates, on utilise l'extension ValuelistExtension qui définit deux fonctions : 

- `valuelist` pour récupérer des valuelist  prenant en paramètre la catégorie. Lorsqu'on veut afficher plusieurs `valuelist` de la même catégorie dans une même page cette méthode est idéale

```yml
valuelist 1 : valuelist(categorie).vl1 ou valuelist(categorie)['vl1']
valuelist 2 : valuelist(categorie).vl2 ou valuelist_label(categorie, vl2) car la categorie a ete mise en cache
```

- `value` pour récupérer le libellé ou la valeur d'une `valuelist` prenant en paramètre la catégorie et le code.

valuelist 1 label : valuelist(categorie).label ou valuelist(categorie)['label']
valuelist 1 value : valuelist(categorie).value ou valuelist(categorie)['value']

Par défaut, la langue utilisée est celle présente dans la `Request`. Cependant, cette locale peut-être surchargée grâce aux derniers paramètres `locale`de ces deux méthodes.

## Divers ##

- Tout récupération d'une `valuelist` est mise en cache.

- La méthode `getValue` cache toute la catégorie.

- Pour la surcharge et pour les test unitaires, il faut typer les paramètres des méthodes avec l'interface `ValuelistManagerInterface` et non la classe du `manager`.

- Le chargement des fixtures fonctionne uniquement pour les `bundles` situés dans /src

- Quand le parent n'est pas spécifié, on ne récupère que les valuelist sans parent.

- Si la langue est spécifiée est différent de `default`, on récupére les `valuelist ayant de la langue `default`en plus.

