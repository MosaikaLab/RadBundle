<?php
namespace Mosaika\RadBundle\Core\Generator;

use Mosaika\RadBundle\Core\Grammar\GrammarClass;
use Mosaika\RadBundle\Core\Grammar\GrammarProperty;
use Mosaika\RadBundle\Core\Grammar\GrammarMethod;
use Mosaika\RadBundle\Core\Grammar\GrammarUtils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mosaika\RadBundle\Core\Model\ControllerAction;
class RadCrudGenerator extends RadGeneratorBase {
	
	protected $entityClass;
	
	protected $optFormtype = true;
	
	protected $routeName;
	
	protected $routePath;

	/**
	 *
	 * @var string
	 */
	protected $controllerSuperClass = \Symfony\Bundle\FrameworkBundle\Controller\Controller::class;
	
	
	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadCrudGenerator
	 */
	public static function get(ContainerInterface $container, $entityClass){
		$builder = new RadCrudGenerator();
		return $builder
		->setEntityClass($entityClass)
		->setContainer($container); 
	}
	public function getIndexAction(){
		$token = "Entity\\";
		$className = $this->bundle . ":" . substr($this->entityClass, strpos($this->entityClass, $token) + strlen($token)); 
		
	    $action = ControllerAction::get()
	    ->setName("index")
	    ->addRoute($this->routeName . "_index",$this->routePath . "/index")
	    ->addContent('$em = $this->getDoctrine()->getEntityManager();')
	    ->addContent('	    $repo = $em->getRepository("'. $className .'");')
	    ->addContent('	    $elements = $repo->findAll();')
	    ->addContent('
        return $this->renderPage($request, "' . $className . ':index.html.twig", [
            "elements" => $elements
        ]);');
	    ;
	    return $action;
	}
	public function buildController(){
	    $gen = RadControllerGenerator::get($this->container)
	    ->addAction($this->getIndexAction())
	    ->setClass($this->class)
	    ->setNamespace($this->namespace)
	    ->setBundle($this->bundle)
	    ->setControllerSuperClass($this->controllerSuperClass)
	    ;
	    
	    $gen->commit();
	    /*
	    die();
		$this->setBaseNamespace("Controller");
		$dir = $this->getWorkingPath();
		$path = $dir . $this->class . ".php";
		if(!file_exists($dir)){
			mkdir($dir,null,true);
		}
		$class = new GrammarClass();
		$class
		->setName($this->class)
		->setNamespace($this->getFullNameSpace())
		->setExtend($this->normalizeNamespace($this->controllerSuperClass))
		;
		
		file_put_contents($path, $class);
		
//		$fileName 	= str_replace("Entity","Controller",$this->class->getName());
	//	$namespace 	= str_replace("Entity","Controller",$this->class->getNamespaceName());
		echo $path;
		die();
		*/
	}
	
    public function commit(){
    	$this->_class = new \ReflectionClass($this->entityClass);
    	$this->buildController();
    	/*
    	die();
	    $path = $this->getWorkingPath();
	    $implFile = $path . $this->getClass() . "Impl.php";
	    $clsFile = $path . $this->getClass() . ".php";
	    $repoFile = $path . $this->getClass() . "Repository.php";
	    $impl = $this->buildClassImpl();
	    $cls = $this->buildClass();
	    $repo = $this->buildRepository();
	    
	    if(!file_exists($path)){
	        mkdir($path,664,true);
	    }
        file_put_contents($implFile, $impl->__toString());
        if(!file_exists($clsFile)){
            file_put_contents($clsFile, $cls->__toString());
        }
        if(!file_exists($repoFile)){
            file_put_contents($repoFile, $repo->__toString());
        }
        */
        return $this;
	}
	
	protected function buildClass(){
		$class = new GrammarClass();
		$class->addUse("Doctrine\\ORM\\Mapping","ORM");
		$class->addAnnotation("ORM\\Entity(repositoryClass=\"" . $this->getFullClass() . "Repository\")");
		$class->addAnnotation("ORM\\Table",array("name" => $this->getTable()));
		if($this->hasLifecycle){
			$class->addAnnotation("ORM\\HasLifecycleCallbacks");
		}
		$class->setNamespace($this->getFullNameSpace());
		$fname = $this->getClass();
		$class->setName($fname);
		$class->setExtend($this->getClass() . "Impl");
		return $class;
	}

	protected function buildRepository(){
		$class = new GrammarClass();
		$class->addUse("Doctrine\\ORM\\EntityRepository");
		
		$class->setNamespace($this->getFullNameSpace());
		$fname = $this->getClass() . "Repository";
		$class->setName($fname);
		$class->setExtend("EntityRepository");
		return $class;
	}
	protected function buildClassImpl(){
	    $impl = new GrammarClass();
		$impl->addUse("Doctrine\\ORM\\Mapping","ORM");
		$impl->addUse("Symfony\\Component\\Validator\\Constraints","Assert");
		$impl->setNamespace($this->getFullNameSpace());
	    $impl->setName($this->getClass() . "Impl");
	    $impl->setExtend($this->getParent());
	    $constructor = GrammarMethod::getInstance()->setName("__construct")->addModifier("public");
	    foreach($this->fields as $f){
	        $prop = GrammarProperty::getInstance()->setName($f->get("name"));
	        $type = $f->get("type");
	    	$nullable = $f->has("required") ? !$f->get("required") : true; 
	        $dbname = GrammarUtils::propertyToDb($prop->getName());
	        if($type=="id"){
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => "integer"));
	            $prop->addAnnotation("ORM\\Id");
	            $prop->addAnnotation("ORM\\GeneratedValue",array("strategy" => "AUTO"));
	            $type = "integer";
	        }else if($type=="service"){
	            $type = "string";
	            /**
	             * options: array(type=service_type_tag, interface?)
	             */
	            $constructor->addContent("\$this->" . $prop->getName() . "='';");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }else if($type=="integer" || $type=="int"){
	            $type = "integer";
	            $constructor->addContent("\$this->" . $prop->getName() . "=0;");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }else if($type=="datetime"){
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	            $type = \DateTime::class;
	        }elseif($type=="file"){
	        	$type = "string";
	        	$prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }elseif($type=="array"){
	        	$type = $f->get("array_type") . "[]";
	        	$prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => "array", "nullable" => $nullable));
	            $constructor->addContent("\$this->" . $prop->getName() . "= array();");
	        }else if($type=="string" || $type == "float" || $type == "double" || $type=="boolean"){
	            $constructor->addContent("\$this->" . $prop->getName() . "='';");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }else if($type=="text"){
	            $constructor->addContent("\$this->" . $prop->getName() . "='';");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	            $type = "string";
	        }else if($type=="mto"){    // MANY TO ONE
	            $args = array("targetEntity" => $f->get("ref"));
	            if($f->has("inversedBy")){
	                $args["inversedBy"] = $f->get("inversedBy");
	            }
	            $prop->addAnnotation("ORM\\ManyToOne",$args);
	            $type = "\\" . $f->get("ref");
	        }else if($type=="otm"){    // ONE TO MANY
	            $mappedBy = strtolower($this->getClass());
	            if($f->has("mappedBy")){
	                $mappedBy = $f->get("mappedBy");
	            }
	            $args = array("targetEntity" => $f->get("ref"), "mappedBy" => $mappedBy);
	            $prop->addAnnotation("ORM\\OneToMany",$args);
	            $type = "\\" . $f->get("ref") . "[]|\\Doctrine\\Common\\Collections\\ArrayCollection";
	        }else if($type=="mtm"){    // MANY TO MANY
	            $constructor->addContent("\$this->" . $prop->getName() . " = new \\Doctrine\\Common\\Collections\\ArrayCollection();");
	            $type = "\\Doctrine\\Common\\Collections\\ArrayCollection|\\" . $f->get("ref") . "[]";;
	            $args = array("targetEntity" => $f->get("ref"));
	            if($f->has("mappedBy")){
	                $args["mappedBy"] = $f->get("mappedBy");
	            }
	            $prop->addAnnotation("ORM\\ManyToMany",$args);
	            $args = array("name" => $f->get("table") );
	            $prop->addAnnotation("ORM\\JoinTable",$args);
	        }else if($type=="object"){
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => true));
	            $type = $f->get("class");
	        }else{
	            throw new \Exception("Invalid type for column " . $f->get("name") . ":" . $type);
	        }
	        $prop->setType($type);
	        $prop->addModifier("protected");
	        $impl->addProperty($prop);
	        
	        if($f->get("type")=="file"){
	        	$methodSuffix = ucfirst($f->get("name"));
	        	$property = $f->get("name");
	        	$thisProperty = "\$this->" . $property;
	        	$propertyFile = $property . "File";
	        	$thisPropertyFile = "\$this->" . $propertyFile;

	        	/*
	        	 * Ispirato da http://brentertainment.com/other/docs/cookbook/doctrine/file_uploads.html
	        	 */ 
	        	
	        	//Creo la property aggiuntiva per il file
	        	$assert = array();
	        	if($f->has("maxSize")){
	        		$assert["maxSize"] = $f->get("maxSize");
	        	}
	        	if($f->has("mimeTypesMessage")){
	        		$assert["mimeTypesMessage"] = $f->get("mimeTypesMessage");
	        	}
	        	if($f->has("mimeTypes ")){
	        		$assert["mimeTypes "] = "{" . implode(",",array_map(function($item){
	        			return "\"" . $item . "\"";
	        		},$f->get("mimeTypes"))) . "}";
	        	}
	        	$impl->addProperty(GrammarProperty::getInstance()
	        			->addModifier("protected")
	        			->setName($propertyFile)
	        			->addAnnotation("Assert\File",$assert)
	        	);
	        	// Creo il metodo getFullPath
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->setName("get" . $methodSuffix . "FullPath")
	        			->addModifier("public")
	        			->addContent("return null === " . $thisProperty . "? null : \$this->get" . $methodSuffix . "UploadRootDir().'/'." . $thisProperty . ";")
	        	);
	        	// Creo il metodo getUploadRootDir
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->setName("get" . $methodSuffix . "UploadRootDir")
	        			->addModifier("protected")
	        			->addContent("return WEB_PATH . self::getIconUploadRootUrl();")
       			);
	        	
	        	// Creo il metodo getUploadRootUrl
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->setName("get" . $methodSuffix . "UploadRootUrl")
	        			->addModifier("public static")
	        			->addContent("return '/upload/icons/';")
       			);
	        	
        		// Method preUpload
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->addModifier("public")
	        			->addContent("if (" . $thisPropertyFile . ") {")
	        			->addContent("\$this->set" . $methodSuffix . "(rand() . \"_\" . time() . \".\" . " . $thisPropertyFile . "->guessExtension());",1)
	        			->addContent("}")
	        			->addAnnotation("ORM\\PrePersist")
	        			->addAnnotation("ORM\\PreUpdate")
	        			->setName("preUpload" . $methodSuffix)
	        	);

	        	// Method upload
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->addModifier("public")
	        			->addContent("if (null === " . $thisPropertyFile . "){")
	        			->addContent("return;",1)
	        			->addContent("}")

	        			->addContent($thisPropertyFile . "->move(")
	        			->addContent("\$this->" . "get" . $methodSuffix . "UploadRootDir" . "()," . $thisProperty,1)
	        			->addContent(");")
	        			->addContent("unset(" . $thisPropertyFile . ");")
	        	
	        			->addAnnotation("ORM\\PrePersist")
	        			->addAnnotation("ORM\\PreUpdate")
	        			->setName("upload" . $methodSuffix)
	        	);
	        	
	        	// Method removeUpload
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->addModifier("public")
	        			->addContent("if (\$file = \$this->get" . $methodSuffix . "FullPath()) {")
	        			->addContent("unlink(\$file);",1)
	        			->addContent("}")
	        			->addAnnotation("ORM\\PostRemove")
	        			->setName("removeUpload" . $methodSuffix)
	        	);

	        }
	        
	        if($f->get("type")=="mtm"){
	            if($f->has("singular")){
	                $singleName = $f->get("singular");
	            }else{
	                $singleName = $prop->getName();
	                if(strrpos($singleName, "s", strlen($singleName)-1) !== FALSE){
	                    $singleName = substr($singleName,0,-1);
	                }
	            }
	            //echo $singleName;
	            $add = GrammarMethod::getInstance()
	            ->setName(GrammarUtils::dbToMethod($singleName,"add"))
	            ->addModifier("public")
	            ->setReturnType("\\" . $impl->getFullName())
	            ->addArgument(GrammarProperty::getInstance()->setName($singleName)->setType("\\" . $f->get("ref")))
                ->addContent("if(!\$this->" . $prop->getName() . "->contains(\$" . $singleName . "))")
	            ->addContent("\$this->" . $prop->getName() . "->add(\$" . $singleName . ");",1)
	            ->addContent("return \$this;")
	            ;
	            $impl->addMethod($add);
	
	            $has = GrammarMethod::getInstance()
	            ->setName(GrammarUtils::dbToMethod($prop->getName(),"has"))
	            ->addModifier("public")
	            ->setReturnType("bool")
	            ->addContent("return sizeof(\$this->" . $prop->getName() . ")>0;")
	            ;
	            $impl->addMethod($has);
	        }
	        $prop->addTypeComment();
	         
	    }
	    $impl->addMethod($constructor);
	    return $impl;
	}
	public function getEntityClass() {
		return $this->entityClass;
	}
	public function setEntityClass($entityClass) {
		$this->entityClass = $entityClass;
		return $this;
	}
	public function getOptFormtype() {
		return $this->optFormtype;
	}
	public function setOptFormtype($optFormtype) {
		$this->optFormtype = $optFormtype;
		return $this;
	}
	public function getControllerSuperClass() {
		return $this->controllerSuperClass;
	}
	public function setControllerSuperClass($controllerSuperClass) {
		$this->controllerSuperClass = $controllerSuperClass;
		return $this;
	}
	
	
	
 
    
}