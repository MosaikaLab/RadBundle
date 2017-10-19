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
		
		// Client
		$entities["client"] = $factory->createEntity("Client","",$bundle)
		->setTableName("client")
		->addField(IdField::create("id"))
		->addField(StringField::create("name"))
		->addField(TextField::create("address"))
		->addField(TextField::create("vat"))
		->addField(DateTimeField::create("createdAt")->setNowDefaultValue())
		->addField(DateTimeField::create("updatedAt")->setNowDefaultValue())
		;
		
		// Quotation
		$entities["quotation"] = $factory->createEntity("Quotation","",$bundle)
		->setTableName("quotation")
		->addField(IdField::create("id"))
		->addField(StringField::create("title"))
		->addField(StringField::create("publishStatus"))
		->addField(TextField::create("num"))
		->addField(DateField::create("date"))
		->addField(IntegerField::create("chance"))
		->addField(DateTimeField::create("createdAt")->setNowDefaultValue()->setUserWritable(false))
		->addField(DateTimeField::create("updatedAt")->setNowDefaultValue()->setUserWritable(false))
		->addField(OneToManyField::create("status", $entityNs . "QuotationStatus"))
		->addField(OneToManyField::create("client",$entityNs . "Client"))
		;
		// Quotation status
		$entities["quotation_status"] = $factory->createEntity("QuotationStatus","",$bundle)
		->setTableName("quotation_status")
		->addField(IdField::create("id"))
		->addField(StringField::create("title"))
		->addField(StringField::create("slug"))
		;
		
		$entities["quotation_payment"] = $factory->createEntity("QuotationPayment","",$bundle)
		->setTableName("quotation_payment")
		->addField(IdField::create("id"))
		->addField(OneToManyField::create("quotation",$entityNs . "Quotation"))
		->addField(IntegerField::create("ordering"))
		->addField(StringField::create("description"))
		->addField(FloatField::create("price"))
		->addField(BooleanField::create("bef"))
		;
		
		// Quotation Items
		$entities["income_item"] = $factory->createEntity("IncomeItem","",$bundle)
		->setTableName("income_item")
		;
		$entities["outcome_item"] = $factory->createEntity("OutcomeItem","",$bundle)
		->setTableName("outcome_item")
		;
		$items = [$entities["outcome_item"], $entities["income_item"]];
		foreach($items as $item){
			$item
			->addField(IdField::create("id"))
			->addField(OneToManyField::create("quotation",$entityNs . "Quotation"))
			->addField(IntegerField::create("ordering"))
			->addField(StringField::create("title"))
			->addField(TextField::create("description"))
			->addField(TextField::create("notes"))
			->addField(TextField::create("color"))
			
			->addField(IntegerField::create("quantity"))
			->addField(FloatField::create("price"))
			->addField(FloatField::create("discount"))
			->addField(StringField::create("discountType"))
			->addField(FloatField::create("total"))
			->addField(FloatField::create("totalNoDiscount"))
			
			->addField(BooleanField::create("bef"))
			->addField(BooleanField::create("hidden"))
			->addField(BooleanField::create("consumptive"))
			->addField(BooleanField::create("alternative"))
			;
		}
		
		$entities["income_item"]->addField(StringField::create("type")->setDefaultValue("outcome"));
		
		$entities["outcome_item"]
		->addField(StringField::create("type")->setDefaultValue("outcome"))
		->addField(BooleanField::create("brf"))
		->addField(BooleanField::create("showVendor"))
		->addField(StringField::create("vendorName"))
		->addField(StringField::create("vendorRif"))
		->addField(FloatField::create("gain"))
		->addField(BooleanField::create("subtract"))
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
		$generator->setJavascriptClientsDirectory("//Volumes//Data//proj//konsole//src//ui//src//app//crm//api//");
		
		
	}
}

