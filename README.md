# RadBundle
Rapid Application Development Bundle for Symfony

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mosaika/radbundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Mosaika\RadBundle\MosaikaRadBundle(),
        );

        // ...
    }

    // ...
}
```


Usage
============

Step 1: Register the Event Listener
-------------------------

```yml
# app/config/services.yml
services:
    app.rad_build_listener:
        class: AppBundle\EventListener\RadBuildListener
        tags:
            - { name: kernel.event_listener, event: rad.build }
```

Step 2: Write the Listener
-------------------------


```php
<?php 
// src/AppBundle/EventListener/RadBuildListener.php
namespace AppBundle\EventListener;
use Mosaika\RadBundle\Event\BuildEvent;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\Field\IdField;
use Mosaika\RadBundle\Model\Field\StringField;
use Mosaika\RadBundle\Model\Field\ManyToOneField;
use Mosaika\RadBundle\Model\Field\IntegerField;
use Mosaika\RadBundle\Model\Field\TextField;
use Mosaika\RadBundle\Model\Field\FloatField;
use Mosaika\RadBundle\Model\Field\DecimalField;
use Mosaika\RadBundle\Model\Field\DateField;
use Mosaika\RadBundle\Model\Field\DateTimeField;
use Mosaika\RadBundle\Model\Field\JsonField;
use Mosaika\RadBundle\Model\Field\OneToManyField;

class RadBuildListener
{
	public function onRadBuild(BuildEvent $event){
		$generator = $event->getGenerator();

	    $bundle = "AppBundle";	// The bundle you are working on
	    $entityNs = "\\AppBundle\\Entity\\";	
	    
	    $entities = [];
	    
	    // Post Category
	    $entities["category"] = RadEntity::create("Category","")
	    ->setTableName("category")
	    ->addField(IdField::create("id"))
	    ->addField(StringField::create("title")->setDefaultValue("Prova"))
	    ->addField(ManyToOneField::create("posts",$entityNs . "Post", "category"))
	    ;
	    
	    // Post
	    $entities["post"] = RadEntity::create("Post","")
	    ->setTableName("post")
	    ->addField(IdField::create("id"))
	    ->addField(StringField::create("title"))
	    ->addField(TextField::create("content"))
	    ->addField(IntegerField::create("fieldInteger"))
	    ->addField(FloatField::create("fieldFloat"))
	    ->addField(DecimalField::create("fieldDecimal"))
	    ->addField(DateField::create("fieldDate"))
	    ->addField(DateTimeField::create("fieldDatetime"))
	    ->addField(JsonField::create("params"))
	    ->addField(OneToManyField::create("category",$entityNs . "Category","posts"))
	    
	    ->createRepository()
	    ->getEntity()
	    ;
	    
	    
	    $generator
	    ->setTablePrefix("app_")
	    ->setBundle($bundle)
	    ;
	    foreach($entities as $e){
	    		$generator->addEntity($e);
	    }
	 }
}

```

Step 3: Run the generator
-------------------------
Use this command to rebuild your files. Note that it will overwrite the generated classes, not the root ones

```console
$ php bin/console rad:build
```

Use this to automatically run doctrine:schema:update command
```console
$ php bin/console rad:build --schema-update
```

