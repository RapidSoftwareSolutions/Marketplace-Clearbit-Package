       <?php
       $routes = [
       'findCompanyLogo',
       'autocompleteCompany',
       'findInfoByEmail',
       'flagCompanyInfoAsIncorrect',
       'findCompany',
       'flagPersonInfoAsIncorrect',
       'subscribeToPersonInfoUpdates',
        'findPerson',
        'metadata'
       ];
       foreach ($routes as $file) {
           require __DIR__ . '/../src/routes/' . $file . '.php';
       }

