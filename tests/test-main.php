<?php

class MainTest extends WP_UnitTestCase {

    function testClass() {
        $tmal = new TMaL();

        $this->assertInstanceOf('TMaL', $tmal);
    }
}