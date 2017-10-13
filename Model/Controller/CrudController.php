<?php
namespace Mosaika\RadBundle\Model\Controller;


use Mosaika\RadBundle\Model\RadController;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadControllerAction;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Mosaika\RadBundle\Model\Controller\Action\SaveActionConfig;
use Mosaika\RadBundle\Model\Controller\Action\ListActionConfig;

class CrudController extends RadController{
	/**
	 * @var string json|html
	 */
    protected $format = "html";
	/**
	 * 
	 * @var RadEntity
	 */
	protected $entity;
	
	/**
	 * @var Container
	 */
	protected $container;

	
    /**
     * @return \Mosaika\RadBundle\Model\RadEntity
     */
	public function getEntity() {
		return $this->entity;
	}
	
	
	/**
	 * @param string $name Action name
	 * @param string $repositoryQuery Method to use for this action
     * @return \Mosaika\RadBundle\Model\Controller\CrudController
	 */
	public function addListAction($name="list", $config=null){
		if(!$config){
			$config = ListActionConfig::create();
		}
		/**
		 * @var EngineInterface $twig
		 */
		$twig = $this->container->get("templating");
		$body = $twig->render("MosaikaRadBundle::templates/controller/crud/list.php.twig", array(
				"config" => $config,
				"format" => $this->format,
				"entity" => $this->entity
				
		));
		$this->actions[] = RadControllerAction::create($name)
		->setBody($body)
		;
		
		return $this;
	}
	/**
	 * @param string $name Action name
	 * @param SaveActionConfig $config Action config
     * @return \Mosaika\RadBundle\Model\Controller\CrudController
	 */
	public function addSaveAction($name="save",$config=null){
		/**
		 * @var EngineInterface $twig
		 */
		$twig = $this->container->get("templating");
		if(!$config){
			$config = SaveActionConfig::create();
		}
		
		$body = $twig->render("MosaikaRadBundle::templates/controller/crud/save.php.twig", array(
			"format" => $this->format,
			"entity" => $this->entity,
			"config" => $config
		));
		$this->actions[] = RadControllerAction::create($name)
		->setBody($body)
		;
		
		return $this;
	}
	
    /**
     * @return \Mosaika\RadBundle\Model\Controller\CrudController
     */
	public function setContainer($c) {
		$this->container = $c;
		return $this;
	}
	
	/**
	 * 
	 * @param RadEntity $entity
     * @return \Mosaika\RadBundle\Model\Controller\CrudController
	 */
	public function setEntity(RadEntity $entity) {
		$this->entity = $entity;
		$this->setName($entity->getName());
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @param string $format
	 * @return CrudController
	 */
	public function setFormat($format) {
		$this->format = $format;
		return $this;
	}
	
}

