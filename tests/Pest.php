<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Unit');

uses()
    ->beforeEach(function () {
        // Additional setup before each test
    })
    ->in('Feature');

uses()
    ->beforeEach(function () {
        // Additional setup before each unit test
    })
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function getHeaders($token) {
    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}