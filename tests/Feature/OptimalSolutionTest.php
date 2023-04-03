<?php

namespace Tests\Feature;

use Tests\TestCase;

class OptimalSolutionTest extends TestCase
{

    public function test_cart_solver_for_two_items()
    {

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJjYXJ0X2lkIjoxNTMsIml0ZW1zIjpbeyJuYW1lIjoiQ05CIEVDIDYwIExQIEt1ZnIgcHJvIGVsZWt0cmlja291IGt5dGFydSIsImltZ191cmwiOiJodHRwczpcL1wvbXV6aWtlcmNkbi5jb21cL3VwbG9hZHNcL3Byb2R1Y3RzXC83NVwvNzUwNVwvbWFpbl85NDQ5Y2M2Ny5qcGciLCJkZXNjcmlwdGlvbiI6IkVsZWN0cmljIEd1aXRhciBDYXNlLCBQbGFzdG92XHUwMGZkIGt1ZnIgcyBwb2xzdHJvdlx1MDBlMW5cdTAwZWRtIGEgcFx1MDE1OWVoclx1MDBlMWRrb3UsIHZob2RuXHUwMGZkIHBybyB2XHUwMTYxZWNobnkgbW9kZWx5IFNpbmdsZWN1dC4ifSx7Im5hbWUiOiJDTkIgTURDIDIwIEEgS3VmciBwcm8gbWFuZG9sXHUwMGVkbnUiLCJpbWdfdXJsIjoiaHR0cHM6XC9cL211emlrZXJjZG4uY29tXC91cGxvYWRzXC9wcm9kdWN0c1wvNzVcLzc1MjhcL21haW5fYTUwMTkyNzguanBnIiwiZGVzY3JpcHRpb24iOiJUdmFyb3Zhblx1MDBmZCBrdWZyIHBybyBtYW5kb2xcdTAwZWRudSBBLXN0eWxlLiBNb2xpdGFub3ZcdTAwZTkgcG9sc3Ryb3ZcdTAwZTFuXHUwMGVkLCBwbGFzdG92XHUwMGUxIHBldm5cdTAwZTEgcnVrb2plXHUwMTY1LCBrb1x1MDE3ZWVuXHUwMGZkIHBvdnJjaCwgZHZhIHpcdTAwZTFta3kuIn1dfQ.8O9qK3Ge9bXKW7FhoN4lPxGhVaZjA76hYKz7oKCPmnNXtqk8vsYCjAAtKnhUctB7I28z77xeY_Ztz8nP6Kc9dQ' ,
        ])->get('/api/solve');

        $response->decodeResponseJson()->assertCount(2);
        $response->decodeResponseJson()->assertFragment([
            "shop_name" => "Kytary",
            "shop_name" => "Music-city"
        ]);

    }








}

