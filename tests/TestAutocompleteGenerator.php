<?php

use atphp\autocomplete_generator\AutocompleteGenerator;

class TestAutocompleteGenerator extends PHPUnit_Framework_TestCase
{

    public function testGenerator()
    {
        $output = (new AutocompleteGenerator())->generate('DateTime');
        $this->assertContains('class DateTime', $output);
        $this->assertContains('const ATOM = "Y-m-d\TH:i:sP"', $output);
        $this->assertContains('public function setDate', $output);
        $this->assertContains('public static function createFromFormat($format, $time', $output);
    }

}
