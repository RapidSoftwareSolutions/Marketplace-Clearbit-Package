<?php
$app->post('/api/Clearbit/findInfoByEmail', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'email']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url']['combined'] . "v2/combined/find";
    $body = array();
    $body['email'] = $post_data['args']['email'];
    if (isset($post_data['args']['webhookUrl']) && strlen($post_data['args']['webhookUrl']) > 0) {
        $body['webhook_url'] = $post_data['args']['webhookUrl'];
    }
    if (isset($post_data['args']['givenName']) && strlen($post_data['args']['givenName']) > 0) {
        $body['given_name'] = $post_data['args']['givenName'];
    }
    if (isset($post_data['args']['familyName']) && strlen($post_data['args']['familyName']) > 0) {
        $body['family_name'] = $post_data['args']['familyName'];
    }
    if (isset($post_data['args']['ipAddress']) && strlen($post_data['args']['ipAddress']) > 0) {
        $body['ip_address'] = $post_data['args']['ipAddress'];
    }
    if (isset($post_data['args']['location']) && strlen($post_data['args']['location']) > 0) {
        $body['location'] = $post_data['args']['location'];
    }
    if (isset($post_data['args']['company']) && strlen($post_data['args']['company']) > 0) {
        $body['company'] = $post_data['args']['company'];
    }
    if (isset($post_data['args']['companyDomain']) && strlen($post_data['args']['companyDomain']) > 0) {
        $body['company_domain'] = $post_data['args']['companyDomain'];
    }
    if (isset($post_data['args']['linkedin']) && strlen($post_data['args']['linkedin']) > 0) {
        $body['linkedin'] = $post_data['args']['linkedin'];
    }
    if (isset($post_data['args']['twitter']) && strlen($post_data['args']['twitter']) > 0) {
        $body['twitter'] = $post_data['args']['twitter'];
    }
    if (isset($post_data['args']['facebook']) && strlen($post_data['args']['facebook']) > 0) {
        $body['facebook'] = $post_data['args']['facebook'];
    }
    if (isset($post_data['args']['webhookId']) && strlen($post_data['args']['webhookId']) > 0) {
        $body['webhook_id'] = $post_data['args']['webhookId'];
    }
    if (isset($post_data['args']['subscribe']) && strlen($post_data['args']['subscribe']) > 0) {
        $body['subscribe'] = $post_data['args']['subscribe'];
    }


    //requesting remote API
    $client = new GuzzleHttp\Client();

    try {

        $resp = $client->request('GET', $query_str, [
            'headers' => [
                'Authorization' => 'Bearer ' . $post_data['args']['apiKey']
            ],
            'query' => $body
        ]);

        $responseBody = $resp->getBody()->getContents();
        $rawBody = json_decode($resp->getBody());
        $errorSet = $rawBody->error;

        $all_data[] = $rawBody;
        if ($response->getStatusCode() == '200' && $errorSet === null) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($all_data) ? $all_data : json_decode($all_data);
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