<?php
$app->post('/api/Clearbit/findCompanyLogo', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['domain']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url']['logo'] . $post_data['args']['domain'];

    //requesting remote API
    $client = new GuzzleHttp\Client();
    if (isset($post_data['args']['format']) && strlen($post_data['args']['format']) > 0) {
        $body['format'] = $post_data['args']['format'];
    }
    if (isset($post_data['args']['size']) && strlen($post_data['args']['size']) > 0) {
        $body['size'] = $post_data['args']['size'];
    }
    if (isset($post_data['args']['greyscale']) && strlen($post_data['args']['greyscale']) > 0) {
        $body['greyscale'] = $post_data['args']['greyscale'];
    }

    $result = [];
    $client->getAsync($query_str,
        [

            'query' => $body,
            'stream' => true
        ]
    )
        ->then(
            function (\Psr\Http\Message\ResponseInterface $response) use ($client, $post_data, $settings, &$result) {
                $responseApi = $response->getBody()->getContents();
                $fileExtension = isset($post_data['args']['format']) ? $post_data['args']['format'] : 'png';
                $fileName = pathinfo($post_data['args']['filePath'])['filename'];

                $size = strlen($responseApi);
                if (in_array($response->getStatusCode(), ['200', '201', '202', '203', '204'])) {
                    try {
                        $fileUrl = $client->post($settings['uploadServiceUrl'], [
                            'multipart' => [
                                [
                                    'name' => 'length',
                                    'contents' => $size
                                ],
                                [
                                    'name' => 'file',
                                    'filename' => $fileName . '.' . $fileExtension,
                                    'contents' => $responseApi
                                ],
                            ]
                        ]);
                        $gcloud = $fileUrl->getBody()->getContents();
                        $resultDecoded = json_decode($gcloud, true);
                        $result['callback'] = 'success';
                        $result['contextWrites']['to'] = ($resultDecoded != NULL) ? $resultDecoded : $gcloud;
                    } catch (GuzzleHttp\Exception\BadResponseException $exception) {
                        $result['callback'] = 'error';
                        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                        $result['contextWrites']['to']['status_msg'] = 'Something went wrong during file link receiving.';
                    }
                } else {
                    $resultDecoded = json_decode($responseApi, true);
                    $result['callback'] = 'error';
                    $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                    $result['contextWrites']['to']['status_msg'] = ($resultDecoded != NULL) ? $resultDecoded : $responseApi;
                }
            },
            function (GuzzleHttp\Exception\BadResponseException $exception) use (&$result) {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = $exception->getMessage();
            },
            function (GuzzleHttp\Exception\ConnectException $exception) use (&$result) {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
                $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';
            }
        )
        ->wait();


    return $response->withHeader('Content-type', 'application/json')->withJson($result, 200, JSON_UNESCAPED_SLASHES);

});