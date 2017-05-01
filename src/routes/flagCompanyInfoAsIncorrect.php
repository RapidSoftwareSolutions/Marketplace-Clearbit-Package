<?php
$app->post('/api/Clearbit/flagCompanyInfoAsIncorrect', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['companyId', 'apiKey']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url']['company'] . "v1/companies/" . $post_data['args']['companyId'] . "/flag";
    $body = array();

    if (isset($post_data['args']['name']) && strlen($post_data['args']['name']) > 0) {
        $body[] = [
            'name' => 'name',
            'contents' => $post_data['args']['name']
        ];
    }

    if (isset($post_data['args']['tags']) && count($post_data['args']['tags']) > 0) {
        $body[] = [
            'name' => 'tags',
            'contents' => $post_data['args']['tags']
        ];
    }
    if (isset($post_data['args']['description']) && strlen($post_data['args']['description']) > 0) {
        $body[] = [
            'name' => 'description',
            'contents' => $post_data['args']['description']
        ];
    }
    if (isset($post_data['args']['raised']) && strlen($post_data['args']['raised']) > 0) {
        $body[] = [
            'name' => 'raised',
            'contents' => $post_data['args']['raised']
        ];
    }
    if (isset($post_data['args']['location']) && strlen($post_data['args']['location']) > 0) {
        $body[] = [
            'name' => 'location',
            'contents' => $post_data['args']['location']
        ];
    }
    if (isset($post_data['args']['facebookHandle']) && strlen($post_data['args']['facebookHandle']) > 0) {
        $body[] = [
            'name' => 'facebook_handle',
            'contents' => $post_data['args']['facebookHandle']
        ];
    }
    if (isset($post_data['args']['crunchbaseHandle']) && strlen($post_data['args']['crunchbaseHandle']) > 0) {
        $body[] = [
            'name' => 'crunchbase_handle',
            'contents' => $post_data['args']['crunchbaseHandle']
        ];
    }
    if (isset($post_data['args']['twitterHandle']) && strlen($post_data['args']['twitterHandle']) > 0) {
        $body[] = [
            'name' => 'twitter_handle',
            'contents' => $post_data['args']['twitterHandle']
        ];
    }
    if (isset($post_data['args']['linkedinHandle']) && strlen($post_data['args']['linkedinHandle']) > 0) {
        $body[] = [
            'name' => 'linkedin_handle',
            'contents' => $post_data['args']['linkedinHandle']
        ];
    }
    if (isset($post_data['args']['employees']) && strlen($post_data['args']['employees']) > 0) {
        $body[] = [
            'name' => 'employees',
            'contents' => $post_data['args']['employees']
        ];
    }
    if (isset($post_data['args']['logo']) && strlen($post_data['args']['logo']) > 0) {
        $body[] = [
            'name' => 'logo',
            'contents' => $post_data['args']['logo']
        ];
    }
    if (isset($post_data['args']['emailProvider']) && strlen($post_data['args']['emailProvider']) > 0) {
        $body[] = [
            'name' => 'email_provider',
            'contents' => $post_data['args']['emailProvider']
        ];
    }
    if (isset($post_data['args']['type']) && strlen($post_data['args']['type']) > 0) {
        $body[] = [
            'name' => 'type',
            'contents' => $post_data['args']['type']
        ];
    }


    //requesting remote API
    $client = new GuzzleHttp\Client();

    try {

        $resp = $client->request('POST', $query_str, [
            'headers' => [
                'Authorization' => 'Bearer ' . $post_data['args']['apiKey']
            ],
            'multipart' => $body
        ]);

        $responseBody = $resp->getBody()->getContents();
        $rawBody = json_decode($resp->getBody());
        $errorSet = $rawBody->error;

        $all_data[] = $rawBody;
        if ($response->getStatusCode() == '200' && $errorSet === null) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = 'success';
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {
        $responseBody = $exception->getResponse()->getReasonPhrase();
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $responseBody;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    } catch (GuzzleHttp\Exception\BadResponseException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }


    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

});