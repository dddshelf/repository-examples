<?php

namespace Domain\Model;

class BodyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \DomainException
     */
    public function emptyContentShouldThrowException()
    {
        new Body('');
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function contentTooShortShouldThrowException()
    {
        new Body('s');
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function contentTooLongShouldThrowException()
    {
        new Body(str_repeat('x', 251));
    }

    /**
     * @test
     */
    public function itShouldTrimContent()
    {
        $body = new Body(' content ');

        $this->assertEquals('content', $body->content());
    }
}
