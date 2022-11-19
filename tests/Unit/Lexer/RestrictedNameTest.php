<?php

namespace Tests\Unit\Lexer;

use Tests\TestCase;
use App\Util\Lexer\RestrictedNames;

class RestrictedNameTest extends TestCase
{
    /** @test */
    public function restrictedUsername()
    {
        $names = RestrictedNames::get();
        $this->assertContains('p', $names);
        $this->assertContains('admin', $names);
        $this->assertNotContains('dansup', $names);
        $this->assertNotContains('earth', $names);
    }
}
