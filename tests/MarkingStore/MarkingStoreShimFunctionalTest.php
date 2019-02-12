<?php

namespace JBJ\Workflow\MarkingStore\Tests\MarkingStore;

use Psr\Log\LoggerInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\PropertyAccess\PropertyAccess;
use JBJ\ComposedCollections\Validator\UuidValidator;
use JBJ\Workflow\MarkingStore\MarkingStore\MarkingStoreShim;
use JBJ\Workflow\MarkingStore\EventListener\StorageListener;
use JBJ\Workflow\MarkingStore\StorageStrategy\InMemoryStrategy;
use PHPUnit\Framework\TestCase;

class MarkingStoreShimFunctionalTest extends TestCase
{
    protected function createAcceptableSubject()
    {
        $subject = new class() {
            private $subjectId;
            public function getSubjectId()
            {
                return $this->subjectId;
            }
            public function setSubjectId($subjectId)
            {
                $this->subjectId = $subjectId;
            }
        };
        return $subject;
    }

    public function testGetMarkingReturnsEmptyArrayIfNotSet()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $strategy = new InMemoryStrategy($logger);
        $listener = new StorageListener($logger, $strategy);
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($listener);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $markingStore = new MarkingStoreShim($dispatcher, $propertyAccessor);
        $subject = $this->createAcceptableSubject();
        $subject->setSubjectId('subject1');
        $this->assertEquals([], $markingStore->getMarking($subject)->getPlaces());
        return $markingStore;
    }

    /**
     * @depends testGetMarkingReturnsEmptyArrayIfNotSet
     */
    public function testSetMarkingStorages($markingStore)
    {
        $subject = $this->createAcceptableSubject();
        $subject->setSubjectId('subject1');
        $expectedplaces = [
            'place1' => 1,
            'place2' => 1,
            'place3' => 1,
        ];
        $marking = new Marking($expectedplaces);
        $markingStore->setMarking($subject, $marking);
        $marking = $markingStore->getMarking($subject);
        $places = $markingStore->getMarking($subject)->getPlaces();
        $this->assertEquals($expectedplaces, $places);
    }
}