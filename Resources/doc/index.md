Getting Started With NuxiaValuelistBundle
==================================

## Prerequisites

This version of the bundle requires Symfony xx+.

### Translations

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](http://symfony.com/doc/current/book/translation.html).

## Installation

Installation is a quick (I promise!) 7 step process:

1. Download NuxiaValuelistBundle using composer
2. Enable the Bundle
3. Configure the NuxiaValuelistBundle
4. Import NuxiaValuelistBundle routing
5. Update your database schema

### Step 1: Download NuxiaValuelistBundle using composer

Add NuxiaValuelistBundle by running the command:

``` bash
$ php composer.phar require nuxia/valuelist "@TODO"
```

Composer will install the bundle to your project's `@TODO` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Nuxia\ValuelistBundle\NuxiaValuelistBundle(),
    );
}
```
### Step 3: Configure the NuxiaValuelistBundle

@TODO 

### Step 4: Import NuxiaValuelistBundle routing files

@TODO

### Step 5: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a new entity, the `Valuelist` class.
For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```

### Next Steps

Now that you have completed the basic installation and configuration of the
NuxiaValuelist, you are ready to learn about more advanced features and usages
of the bundle.

The following documents are available:

@TODO
