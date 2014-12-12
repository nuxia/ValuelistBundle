ValuelistBundle
=============

Ajouter une description avec feature

Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation for master](https://github.com/nuxia/ValuelistBundle/blob/master/Resources/doc/index.md)

Installation
------------

All the installation instructions are located in the documentation.

Contributing
-------

Here are a few rules to follow in order to ease code reviews, and discussions before maintainers accept and merge your work.

* You **MUST** follow the [PSR-1](http://www.php-fig.org/psr/1/) and [PSR-2](http://www.php-fig.org/psr/2/).
* You **MUST** run the test suite.
* You **MUST** write (or update) unit tests.
* You **SHOULD** write documentation.
* You **MUST** Toutes les traductions doivent être utilisé le translation_domain par défaut (trans) et le translation domain par défaut doit être NuxiaValueList
* You **MUST** Il faut utiliser les localisations de template avec : pour pouvoir les surcharger dans app (Bundle:Directory:template.html.twig)
* You **MUST** Dans les form type, il faut préciser le trnaslation_domain sur chaque champ afin d'être le plus flexible possible
* You **MUST** Dans le validation.yml les champs doivent TOUS être sous un validation_groups et les messages doivent être uniques

Please, write [commit messages that make sense](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html), and [rebase your branch](http://git-scm.com/book/en/Git-Branching-Rebasing) before submitting your Pull Request.

Also, when creating your Pull Request on GitHub, you **MUST** write a description which gives the context and/or explains why you are creating it.

Thank you!

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE