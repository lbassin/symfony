<?php

namespace Symfony\Component\Workflow\Tests\MarkingStore;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Tests\Subject;

class MethodMarkingStoreTest extends TestCase
{
    public function testGetSetMarkingWithMultipleState()
    {
        $subject = new Subject();

        $markingStore = new MethodMarkingStore(false);

        $marking = $markingStore->getMarking($subject);

        $this->assertInstanceOf(Marking::class, $marking);
        $this->assertCount(0, $marking->getPlaces());

        $marking->mark('first_place');

        $markingStore->setMarking($subject, $marking);

        $this->assertSame(['first_place' => 1], $subject->getMarking());

        $marking2 = $markingStore->getMarking($subject);

        $this->assertEquals($marking, $marking2);
    }

    public function testGetSetMarkingWithSingleState()
    {
        $subject = new Subject();

        $markingStore = new MethodMarkingStore(true);

        $marking = $markingStore->getMarking($subject);

        $this->assertInstanceOf(Marking::class, $marking);
        $this->assertCount(0, $marking->getPlaces());

        $marking->mark('first_place');

        $markingStore->setMarking($subject, $marking);

        $this->assertSame('first_place', $subject->getMarking());

        $marking2 = $markingStore->getMarking($subject);

        $this->assertEquals($marking, $marking2);
    }
}
