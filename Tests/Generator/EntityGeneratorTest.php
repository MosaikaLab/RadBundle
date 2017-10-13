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
use Mosaika\RadBundle\Model\Query\RadQuery;
use Mosaika\RadBundle\Model\Query\RadQueryFilter;


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
        $repositories = [];
        $controllers = [];
        
        
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
        ->addField(DateTimeField::create("publish_date"))
        ->addField(JsonField::create("params"))
        ->addField(OneToManyField::create("category",$entityNs . "Category","posts"))
        ;
        
        
        $repositories["post"] = 
        
        // Create Repository
        $factory->createEntityRepository($entities["post"])
        
        //Create query for recent posts
        ->createQuery("recent")
        		->setMaxResult(10)
        		->addOrderBy("publish_date","desc")
        		->getRepository()
        		
        	//Create query for category page
        	->createQuery("category")
	        	->addOrderBy("publish_date","desc")
	        	->createFilter()
	        		->setScope("category")
	        		->setSource(RadQueryFilter::SOURCE_REQUEST)
	        	->getQuery()->getRepository()
	        	
	    //Create a fulltext search with request variable     	
        	->createQuery("search")
	        	->addOrderBy("publish_date","desc")
	        	->createFilter()
		        	//Fulltext search
		        ->setScope(["content","title"])
		        // Default operator is =
		        ->setOperator("%like%")
	        		->setExposed()
	        		->setSource(RadQueryFilter::SOURCE_REQUEST)
	        		// Define the request parameter key (querystring or post)
	        		->setValue("s")
	    		->getQuery()->getRepository()    		
        ;
        
        $repositories["category"] = $factory->createEntityRepository($entities["category"]);
        
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
        foreach($entities as $entityKey => $entity){
	        	$crud = new CrudController(null, "Api", $bundle);
	        	$crud
	        	->setFormat("json")
	        	->setEntity($entity)
	        	->setContainer($this->container)
	        	->addListAction("list",ListActionConfig::create()->exposeAll())
	        	->addSaveAction()
	        	;
	        	$generator->addController($crud,$entityKey);
	        	$generator->addEntity($entity,$entityKey);
        }
        foreach($controllers as $controllerKey => $controller){
        	$generator->addController($controller,$controllerKey);
        }
        foreach($repositories as $repositoryKey => $repository){
        		$generator->addRepository($repository,$repositoryKey);
        }
        
        $generator->commit();
        
        $this->assertContains('Hello World', "Hello World");
    }
}
