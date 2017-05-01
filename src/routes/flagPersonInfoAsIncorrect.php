<?php
$app->post('/api/Clearbit/flagPersonInfoAsIncorrect', function ($request, $response, $args) {
    $settings = $this->settings;

    //checking properly formed json
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['personId', 'apiKey']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }
    //forming request to vendor API
    $query_str = $settings['api_url']['person'] . "v1/people/" . $post_data['args']['personId'] . "/flag";
    $body = array();

    if (isset($post_data['args']['givenName']) && strlen($post_data['args']['givenName']) > 0) {
        $body[] = [
            'name' => 'given_name',
            'contents' => $post_data['args']['givenName']
        ];
    }

    if (isset($post_data['args']['familyName']) && strlen($post_data['args']['familyName']) > 0) {
        $body[] = [
            'name' => 'family_name',
            'contents' => $post_data['args']['familyName']
        ];
    }
    if (isset($post_data['args']['employmentTitle']) && strlen($post_data['args']['employmentTitle']) > 0) {
        $body[] = [
            'name' => 'employment_title',
            'contents' => $post_data['args']['employmentTitle']
        ];
    }
    if (isset($post_data['args']['facebookHandle']) && strlen($post_data['args']['facebookHandle']) > 0) {
        $body[] = [
            'name' => 'facebook_handle',
            'contents' => $post_data['args']['facebookHandle']
        ];
    }
    if (isset($post_data['args']['githubHandle']) && strlen($post_data['args']['githubHandle']) > 0) {
        $body[] = [
            'name' => 'github_handle',
            'contents' => $post_data['args']['githubHandle']
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
    if (isset($post_data['args']['googleplusHandle']) && strlen($post_data['args']['googleplusHandle']) > 0) {
        $body[] = [
            'name' => 'googleplus_handle',
            'contents' => $post_data['args']['googleplusHandle']
        ];
    }
    if (isset($post_data['args']['aboutmeHandle']) && strlen($post_data['args']['aboutmeHandle']) > 0) {
        $body[] = [
            'name' => 'aboutme_handle',
            'contents' => $post_data['args']['aboutmeHandle']
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