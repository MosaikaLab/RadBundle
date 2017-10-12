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
        
        $generator
        ->setTablePrefix("app_")
        ->setBundle($bundle)
        
        // Test Entity
        ->addEntity(
            RadEntity::create("Category","")
            ->setTableName("category")
            ->addField(IdField::create("id"))
            ->addField(StringField::create("title")->setDefaultValue("Prova"))
            ->addField(ManyToOneField::create("posts",$entityNs . "Post", "category"))
            )
            
            // Test Entity
        ->addEntity(
            RadEntity::create("Post","")
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
            )
            
            
        ;
        
        $generator->commit();
        
        $this->assertContains('Hello World', "Hello World");
    }
}
