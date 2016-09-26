<?php
namespace minhhoit\oauth2\controller;

use yii\console\Controller;
use minhhoit\oauth2\models\AuthorizationCode;
use minhhoit\oauth2\models\RefreshToken;
use minhhoit\oauth2\models\AccessToken;

/**
 * @author Andrew
 * @since 1.0
 */
class Oauth2Controller extends Controller
{

    public function actionClear()
    {
        AuthorizationCode::deleteAll(['<', 'expires', time()]);
        RefreshToken::deleteAll(['<', 'expires', time()]);
        AccessToken::deleteAll(['<', 'expires', time()]);
    }
}