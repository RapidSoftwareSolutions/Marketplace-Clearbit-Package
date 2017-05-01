<?php
$app->post('/api/Clearbit/findCompany', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['apiKey', 'domain']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url']['company'] . "v2/companies/find";
    $body = array();
    $body['domain'] = $post_data['args']['domain'];

    if (isset($post_data['args']['webhookUrl']) && strlen($post_data['args']['webhookUrl']) > 0) {
        $body['webhook_url'] = $post_data['args']['webhookUrl'];
    }
    if (isset($post_data['args']['companyName']) && strlen($post_data['args']['companyName']) > 0) {
        $body['company_name'] = $post_data['args']['companyName'];
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