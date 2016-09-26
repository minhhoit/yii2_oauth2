<?php
namespace minhhoit\oauth2;

use yii\web\Response;

/**
 *
 * @author Andrew
 * @since 1.0
 */
class RedirectException extends Exception
{

    /**
     * @param string $redirect_uri
     * @param string $error_description (optional)
     * @param string $error A single error code
     * @param string $state Cross-Site Request Forgery
     */
    public function __construct($redirect_uri, $error_description = null, $error = self::INVALID_REQUEST, $state = null)
    {
        parent::__construct($error_description, $error);

        $query = ['error' => $error];

        if ($error_description) {
            $query['error_description'] = $error_description;
        }
        $request = \Yii::$app->request;

        if ($state) {
            $query['state'] = $state;
        }
        \Yii::$app->response->redirect(http_build_url($redirect_uri, [
            'query' => http_build_query($query)
        ], HTTP_URL_REPLACE | HTTP_URL_JOIN_QUERY));
    }
}