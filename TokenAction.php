<?php
namespace minhhoit\oauth2;

use yii\web\Response;

/**
 *
 * @author Andrew
 * @since 1.0
 *
 */
class TokenAction extends \yii\base\Action
{

    public $grantTypes = [
        'authorization_code' => 'minhhoit\oauth2\granttypes\Authorization',
        'refresh_token'      => 'minhhoit\oauth2\granttypes\RefreshToken',
    ];

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->controller->enableCsrfValidation = false;
    }

    public function run()
    {
        if (!$grantType = BaseModel::getRequestValue('grant_type')) {
            throw new Exception('The grant type was not specified in the request');
        }
        if (isset($this->grantTypes[$grantType])) {
            $grantModel = \Yii::createObject($this->grantTypes[$grantType]);
        } else {
            throw new Exception("An unsupported grant type was requested", Exception::UNSUPPORTED_GRANT_TYPE);
        }

        $grantModel->validate();

        \Yii::$app->response->data = $grantModel->getResponseData();
    }
}