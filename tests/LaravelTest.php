<?php

class LaravelTest extends TestCase
{
    public function test_laravel()
    {
        $this->visit('/')
            ->assertResponseStatus(200)
            ->see('Laravel');
    }
}
