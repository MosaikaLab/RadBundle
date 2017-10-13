<?php

namespace Mosaika\RadBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Mosaika\RadBundle\Core\Generator\RadGenerator;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\Field\IdField;
use Mosaika\RadBundle\Model\Field\StringField;
use Mosaika\RadBundle\Model\Field\TextField;
use Mosaika\RadBundle\Model\Field\ManyToOneField;
use Mosaika\RadBundle\Model\Field\OneToManyField;
use Mosaika\RadBundle\Model\Field\JsonField;
use Mosaika\RadBundle\Model\Field\IntegerField;
use Mosaika\RadBundle\Model\Field\FloatField;
use Mosaika\RadBundle\Model\Field\DecimalField;
use Mosaika\RadBundle\Model\Field\DateField;
use Mosaika\RadBundle\Model\Field\DateTimeField;
use Mosaika\RadBundle\Model\RadController;
use Mosaika\RadBundle\Model\RadControllerAction;
use Mosaika\RadBundle\Model\Controller\CrudController;
use Mosaika\RadBundle\Factory\RadFactory;
use Mosaika\RadBundle\Model\Controller\Action\ListActionConfig;


class EntityGeneratorTest extends KernelTestCase
{
    /**
     * 
     * @var Container
     */
    private $container;
    
    public function setUp(){
        self::bootKernel();
        
        $this->container = self::$kernel->getContainer();
    }
    
    public function testEntity(){
        
        $generator = new RadGenerator($this->container);
        
        $bundle = "AppBundle";
        $entityNs = "\\AppBundle\\Entity\\";
        
        /**
         * @var RadFactory $factory
         */
        $factory = $this->container->get("rad.factory");
        
        $entities = [];
        
        // Post Category
        $entities["category"] = $factory->createEntity("Category","",$bundle)
        ->setTableName("category")
        ->addField(IdField::create("id"))
        ->addField(StringField::create("title")->setDefaultValue("Prova"))
        ->addField(ManyToOneField::create("posts",$entityNs . "Post", "category"))
        ;
        
        // Post
        $entities["post"] = $factory->createEntity("Post","",$bundle)
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
        ;
        
        $factory->createEntityRepository($entities["post"]);
        $factory->createEntityRepository($entities["category"]);
        
        $controllers = [];
        /*
        $controllers["api.post"] = (new RadController("Post", "Api"))
        	->setBaseUrl("api/post/")
        	->setBaseRoute("api_post")
        	->addAction(
        		RadControllerAction::create("list")		
        	)
        ;
        */
        
        
        $generator
        ->setTablePrefix("app_")
        ->setBundle($bundle)
        ;
        foreach($entities as $e){
	        	$crud = new CrudController(null, "api");
	        	$crud
	        	->setFormat("json")
	        	->setEntity($e)
	        	->setContainer($this->container)
	        	->addListAction("list",ListActionConfig::create()->exposeAll())
	        	->addSaveAction()
	        	;
	        	$generator->addController($crud);
	        	$generator->addEntity($e);
        }
        foreach($controllers as $controller){
        		$generator->addController($controller);
        }
        
        $generator->commit();
        
        $this->assertContains('Hello World', "Hello World");
    }
}
