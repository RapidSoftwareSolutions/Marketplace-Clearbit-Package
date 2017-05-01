<?php

namespace Test\Functional;

require_once(__DIR__ . '/../../src/Models/checkRequest.php');
class ClearbitTest extends BaseTestCase {

    public function testListMetrics() {

        $routes = [
            'findCompanyLogo',
            'autocompleteCompany',
            'findInfoByEmail',
            'flagCompanyInfoAsIncorrect',
            'findCompany',
            'flagPersonInfoAsIncorrect',
            'subscribeToPersonInfoUpdates',
            'findPerson'

        ];

        foreach($routes as $file) {
            $var = '{  
                    }';
            $post_data = json_decode($var, true);

            $response = $this->runApp('POST', '/api/Clearbit/'.$file, $post_data);

            $this->assertEquals(200, $response->getStatusCode(), 'Error in '.$file.' method');
        }
    }

}
