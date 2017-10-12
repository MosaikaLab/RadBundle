<?php
namespace Mosaika\RadBundle\Model\Controller;


use Mosaika\RadBundle\Model\RadController;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Model\RadControllerAction;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class CrudController extends RadController{
    
	/**
	 * 
	 * @var RadEntity
	 */
	protected $entity;
	
	/**
	 * @var Container
	 */
	protected $container;

    public function getAnnotations(){
        return [
            sprintf('@Doctrine\ORM\Mapping\\Column(name="%s",type="integer")',$this->name),
            "@Doctrine\ORM\Mapping\\Id",
            "@Doctrine\ORM\Mapping\\GeneratedValue(strategy=\"AUTO\")"
        ];
    }
    /**
     * @return \Mosaika\RadBundle\Model\RadEntity
     */
	public function getEntity() {
		return $this->entity;
	}
	public function getActions(){
		$actions = parent::getActions();
		/**
		 * @var EngineInterface $twig
		 */
		$twig = $this->container->get("templating");
		$actions[] = RadControllerAction::create("list")
			->setBody($twig->render("MosaikaRadBundle::templates/controller/crud/list.php.twig"));
		return $actions;
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
	
}

