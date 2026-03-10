<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TineClientBasicTest extends TestCase
{
    public function testTineClientClassExists(): void
    {
        $this->assertTrue(class_exists(\TineClient::class), 'Expected TineClient class to exist');
    }

    public function testTineClientHasExpectedPublicMethods(): void
    {
        $this->assertTrue(method_exists(\TineClient::class, 'getConfig'));
        $this->assertTrue(method_exists(\TineClient::class, 'login'));
        $this->assertTrue(method_exists(\TineClient::class, 'logout'));
        $this->assertTrue(method_exists(\TineClient::class, '__call'));
    }
}

