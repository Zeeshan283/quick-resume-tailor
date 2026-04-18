<?php

namespace Tests\Unit;

use App\Engines\ValidationEngine;
use Exception;
use PHPUnit\Framework\TestCase;

class ValidationEngineTest extends TestCase
{
    private ValidationEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new ValidationEngine();
    }

    public function test_it_allows_valid_tailoring()
    {
        $base = [
            'skills' => ['php', 'laravel'],
            'experience' => [
                ['company' => 'Tech Corp', 'role' => 'Developer']
            ]
        ];

        $output = [
            'skills' => ['Laravel'], // subset/case change
            'experience' => [
                ['company' => 'tech corp', 'role' => 'developer'] // case change
            ]
        ];

        // Should not throw
        $this->engine->validateOutput($base, $output);
        $this->assertTrue(true);
    }

    public function test_it_throws_on_hallucinated_company()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Validation Failure");

        $base = [
            'skills' => [],
            'experience' => [
                ['company' => 'Tech Corp', 'role' => 'Developer']
            ]
        ];

        $output = [
            'skills' => [],
            'experience' => [
                ['company' => 'Facebook', 'role' => 'Developer']
            ]
        ];

        $this->engine->validateOutput($base, $output);
    }

    public function test_it_throws_on_hallucinated_skill()
    {
        $this->expectException(Exception::class);

        $base = [
            'skills' => ['php'],
            'experience' => []
        ];

        $output = [
            'skills' => ['php', 'python'],
            'experience' => []
        ];

        $this->engine->validateOutput($base, $output);
    }
}
