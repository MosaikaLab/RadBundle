<?php
namespace Mosaika\RadBundle\Core\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Mosaika\RadBundle\Model\RadEntity;
use Mosaika\RadBundle\Core\GrammarUtils;

class RadEntityGenerator extends RadGeneratorBase {
	
	protected $table;

	
	private $hasLifecycle = false;
	
	
	protected $parent = "";

	/**
	 * 
	 * @var bool
	 */
	protected $crud;
	
	/**
	 * 
	 * @var GrammarClass
	 */
	protected $impl;
	
	/**
	 *
	 * @return \Mosaika\RadBundle\Core\Generator\RadEntityGenerator
	 */
	public static function get(ContainerInterface $container){
		$builder = new RadEntityGenerator();
		return $builder
		->setBaseNamespace("Entity")
		->setContainer($container)
		->clearFields();
	}
	
	public function commit(RadEntity $entity){
	    $modelPath = $this->getWorkingPath("Model");
	    if(!file_exists($modelPath)){
	        mkdir($modelPath);
	    }
	    $entityPath = $this->getWorkingPath();
	    if(!file_exists($entityPath)){
	        mkdir($entityPath);
	    }
	    
	    $modelNs = new PhpNamespace($this->findNamespace("Model"));
	    $modelNs
	    ->addUse("Doctrine\ORM\Mapping","ORM")
	    ->addUse("Symfony\Component\Validator\Constraints","Assert")
	    ;
	    
	    $modelClass = (new ClassType($entity->getName() . "Model", $modelNs))
	    ->setAbstract(true)
	    ;
	    
	    foreach($entity->getFields() as $field){
	        $name = $field->getName();
	        $dbname = GrammarUtils::propertyToDb($name);
	        $value = null;
	        $property = $modelClass->addProperty($name);
	        $annotations = [];
	        $phpType = strtolower($field->getType());
	        switch($phpType){
	            case 'id':
	                $annotations[] = sprintf('@ORM\Column(name="%s",type="integer")',$dbname);
	                $annotations[] = '@ORM\Id';
	                $annotations[] = '@ORM\GeneratedValue(strategy="AUTO")';
	                $phpType = "integer";
	                break;
	                
	            case 'text':
	                $phpType = "string";
	            case 'string':
	                $annotations[] = sprintf('@ORM\Column(name="%s",type="%s")',$dbname, $phpType);
	                break;
	            case 'otm':
	            case 'mto':
	            case 'onetomany':
	            case 'manytoone':
	                $ref = $field->getArg("ref");
	                $mappedBy = $field->getArg("mappedBy");
	                if(!$ref){
	                    throw new \Exception("OneToMany field should have a ref arg");
	                }
	                if(strpos($ref,"\\")!==0)
	                    $ref = "\\" . $ref;
	                $phpType = $ref . "[]";
	                if($phpType=="otm" || $phpType=="onetomany"){
	                    $doctrineType = "OneToMany";
	                }else{
	                    $doctrineType = "ManyToOne";
	                }
                    $annotations[] = sprintf('@ORM\\'. $doctrineType .'(targetEntity="%s"' . ($mappedBy ? ',mappedBy="%s"' : '') . ')',$ref,$mappedBy);
	                break;
	        }
	        $annotations[] = '@var ' . $phpType;
	        foreach($annotations as $a){
	            $property->addComment($a);
	        }
	        $property->setVisibility("protected");
	        
	        $setter = $modelClass->addMethod(GrammarUtils::propertyToMethod(ucfirst($name),"set"));
	        $setter->addParameter($name);
	        $setter->addComment("@return \\" . $modelClass->getNamespace()->getName() . "\\" . $modelClass->getName());
	        $setter->addBody(sprintf('$this->%s = $%s',$name, $name));
	        $setter->addBody('return $this;');
	        ;
	        
	        $getter = $modelClass->addMethod(GrammarUtils::propertyToMethod(ucfirst($name),"get"));
	        $getter->addComment("@return " . $phpType);
	        $getter->addBody(sprintf('return $this->%s;',$name));
	        ;
	        
	    }
	    
	    
	    
	    
	    
	    
	    
	    echo $modelClass . PHP_EOL;
	    echo "Rad entity commit: " . $entityPath . PHP_EOL;
        
        return;
        
	    $implPath = $this->getWorkingPath() . "Impl" . DIRECTORY_SEPARATOR;
	    $implFile = $implPath . $this->getClass() . "Impl.php";
	    $clsFile = $path . $this->getClass() . ".php";
	    $repoFile = $path . $this->getClass() . "Repository.php";
	    $this->impl = $impl = $this->buildClassImpl();
	    $cls = $this->buildClass();
	    $repo = $this->buildRepository();

	    if(!file_exists($path)){
	    	mkdir($path,774,true);
	    }
	    if(!file_exists($implPath)){
	        mkdir($implPath,774,true);
	    }
        file_put_contents($implFile, $impl->__toString());
        echo "Writing class " . $impl->getFullName() . "\n";
       
        if(!file_exists($clsFile)){
            file_put_contents($clsFile, $cls->__toString());
        	echo "Writing class " . $cls->getFullName() . "\n";
        }else{
        	echo "Skipping class " . $cls->getFullName() . ": already exists\n";
        }
        if(!file_exists($repoFile)){
            file_put_contents($repoFile, $repo->__toString());
        	echo "Writing class " . $repo->getFullName() . "\n";
        }else{
        	echo "Skipping class " . $repo->getFullName() . ": already exists\n";
        }
        return $this;
	}
	public function setLifeCycle($b){
		$this->hasLifecycle = $b;
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
		$class->setExtend("\\" . $this->impl->getFullName());
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
		$impl->setNamespace($this->getFullNameSpace() . "\\Impl");
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
	        }else if($type=="decimal" || $type=="double"){
	            $type = "double";
	            $constructor->addContent("\$this->" . $prop->getName() . "=0;");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => "float", "nullable" => $nullable));
	        }else if($type=="datetime" || $type=="date"){
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	            $type = \DateTime::class;
	        }elseif($type=="file"){
	        	$type = "string";
	        	$prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }elseif($type=="array"){
	        	$type = $f->get("array_type") . "[]";
	        	$prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => "array", "nullable" => $nullable));
	            $constructor->addContent("\$this->" . $prop->getName() . "= array();");
	            $impl->addMethod(
	            		GrammarMethod::getInstance()
	            		->setName("add" . ucfirst($f->get("name")))
	            		->addArgument(GrammarProperty::getInstance()->setName("element")->setType($f->get("array_type")))
	            		->addModifier("public")
	            		->addContent("\$this->" . $f->get("name") . "[] = \$element;")
	            		->addContent("return \$this;")
	            );
	        }else if($type=="string" || $type == "float" || $type == "double" || $type=="boolean"){
	            $constructor->addContent("\$this->" . $prop->getName() . "='';");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	        }else if($type=="text"){
	            $constructor->addContent("\$this->" . $prop->getName() . "='';");
	            $prop->addAnnotation("ORM\\Column",array("name" => $dbname, "type" => $type, "nullable" => $nullable));
	            $type = "string";
	        }else if($type=="mto"){    // MANY TO ONE
	        	$ref = $f->get("ref");
	        	if($ref == "_self"){
	        		$ref = $this->getFullClass();
	        	}
	            $args = array("targetEntity" => $ref);
	            if($f->has("inversedBy")){
	                $args["inversedBy"] = $f->get("inversedBy");
	            }
	            if($f->has("cascadePersist")){
	                $args["cascade"] = array("persist");//$f->get("inversedBy");
	            }
	            $prop->addAnnotation("ORM\\ManyToOne",$args);
	            $type = GrammarUtils::normalizeFullClass($ref);
	        }else if($type=="otm"){    // ONE TO MANY
	        	$ref = $f->get("ref");
	        	if($ref == "_self"){
	        		$ref = $this->getFullClass();
	        	}
	            $mappedBy = strtolower($this->getClass());
	            if($f->has("mappedBy")){
	                $mappedBy = $f->get("mappedBy");
	            }
	            $args = array("targetEntity" => $ref, "mappedBy" => $mappedBy);
	            if($f->has("cascadePersist")){
	                $args["cascade"] = array("persist");//$f->get("inversedBy");
	            }
	            $prop->addAnnotation("ORM\\OneToMany",$args);
	            $type = GrammarUtils::normalizeFullClass($ref) . "[]|\\Doctrine\\Common\\Collections\\ArrayCollection";
	        }else if($type=="mtm"){    // MANY TO MANY
	            $constructor->addContent("\$this->" . $prop->getName() . " = new \\Doctrine\\Common\\Collections\\ArrayCollection();");
	            $type = "\\Doctrine\\Common\\Collections\\ArrayCollection|\\" . $f->get("ref") . "[]";;
	            $args = array("targetEntity" => $f->get("ref"));
	            if($f->has("mappedBy")){
	                $args["mappedBy"] = $f->get("mappedBy");
	            }
	            if($f->has("cascadePersist")){
	                $args["cascade"] = array("persist");//$f->get("inversedBy");
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
	        			->addContent("return WEB_PATH . self::get" . $methodSuffix . "UploadRootUrl();")
       			);
	        	
	        	// Creo il metodo getUploadRootUrl
	        	$impl->addMethod(
	        			GrammarMethod::getInstance()
	        			->setName("get" . $methodSuffix . "UploadRootUrl")
	        			->addModifier("public static")
	        			->addContent("return '/upload/". strtolower($this->class). "/" . $property ."/';")
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
	        			->addContent("if(file_exists(\$file)){",1)
	        			->addContent("unlink(\$file);",2)
	        			->addContent("}",1)
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
	
    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function addField($name, $type="string", $options=array()){
        $field = new ParameterBag();
        $field->set("name", $name);
        $field->set("type", $type);
        foreach($options as $o => $v){
            $field->set($o, $v);
        }
        $this->fields[] = $field;
        return $this;
    }
    public function getField($name){
        foreach($this->fields as $f){
            if($f->get("name")==$name)
                return $f;
        }
    }
    public function clearFields(){
        $this->fields = array();
        return $this;
    }
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
	public function getCrud() {
		return $this->crud;
	}
	public function setCrud($crud) {
		$this->crud = $crud;
		return $this;
	}
	
 
 
    
}