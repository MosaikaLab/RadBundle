<?php

namespace Mosaika\RadBundle\EventListener;

use Mosaika\RadBundle\Model\Field\IdField;
use Mosaika\RadBundle\Model\Field\StringField;
use Mosaika\RadBundle\Model\Field\DateTimeField;
use Mosaika\RadBundle\Model\Field\TextField;
use Mosaika\RadBundle\Model\Field\OneToManyField;
use Mosaika\RadBundle\Model\Controller\RestController;
use Mosaika\RadBundle\Model\Controller\Action\ListActionConfig;
use Mosaika\RadBundle\Model\Field\DateField;
use Mosaika\RadBundle\Model\Field\ManyToOneField;
use Mosaika\RadBundle\Model\Field\JsonField;
use Mosaika\RadBundle\Model\Query\RadQueryFilter;
use Mosaika\RadBundle\Event\BuildEvent;
use Mosaika\RadBundle\Model\Field\IntegerField;
use Mosaika\RadBundle\Model\Field\FloatField;
use Mosaika\RadBundle\Model\Field\BooleanField;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

class RadListenerTest {
	public function onRadBuild(BuildEvent $event){
		return;
		$generator = $event->getGenerator();
		$container = $generator->getContainer();
		
		$bundle = "AppBundle";
		$entityNs = "\\AppBundle\\Entity\\";
		
		/**
		 * @var RadFactory $factory
		 */
		$factory = $container->get("rad.factory");
		
		$generator 
		->setTablePrefix("app_")
		->setBundle($bundle)
		;
		
		$entities = [];
		$repositories = [];
		$controllers = [];
		$clients = [];
		
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
		->addField(DateTimeField::create("publishDate"))
		->addField(JsonField::create("params"))
		->addField(OneToManyField::create("category",$entityNs . "Category","posts"))
		;
		
		
		foreach($entities as $entityKey => $entity){
			$crud = new RestController(null, "Api", $bundle);
			$crud->setFormat("json")
			->setEntity($entity)
			->setContainer($container)
			->setBaseUrl("api/" . strtolower($entity->getName()))
			;
			$crud
			->addListAction("list",ListActionConfig::create()->exposeAll())	// Get list of entities
			->addPostAction() // Insert new entity
			->addGetAction()	// Get single entity
			->addPutAction() // Edit an entity
			;
			$controllers[$entityKey] = $crud;
			$generator->addController($crud,$entityKey);
			$generator->addEntity($entity,$entityKey);
		}
		
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
		->createFilter("category")
		->setScope("category")
		->setSource(RadQueryFilter::SOURCE_REQUEST)
		->getQuery()->getRepository()
		
		//Create a fulltext search with request variable
		->createQuery("search")
		->addOrderBy("publish_date","desc")
		->createFilter("content")
		//Fulltext search
		->setScope("content")
		// Default operator is =
		->setOperator("%like%")
		->setExposed()
		//->setSource(RadQueryFilter::SOURCE_REQUEST)
		// Define the request parameter key (querystring or post)
		->setValue("s")
		->getQuery()->getRepository()
		;
		
		$controllers["post"]->exposeQuery($repositories["post"]->getQuery("recent"),array(
				
		));
		$controllers["post"]->exposeQuery($repositories["post"]->getQuery("search"),array(
				
		));
		
		foreach($controllers as $key => $controller){
			if($controller instanceof RestController){
				$generator->addJavascriptClient($controller,$key);
			}
		}
		
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
		
		
		foreach($controllers as $controllerKey => $controller){
			$generator->addController($controller,$controllerKey);
		}
		foreach($repositories as $repositoryKey => $repository){
			$generator->addRepository($repository,$repositoryKey);
		}
		
		
	}
}

