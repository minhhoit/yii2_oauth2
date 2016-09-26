# yii2_oauth2
This extension provides implementation of Oauth 2.0 specification using Yii2.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require minhhoit/oauth2 "*"
```
or add

```
"minhhoit/oauth2": "*"
```

to the ```require``` section of your `composer.json` file.

To create database tables run migration command
```
$ yii migrate --migrationPath=@minhhoit/oauth2/migrations
```

## Usage

OAuth 2.0 Authorization usage 
```php
namespace app\controllers;

use app\models\LoginForm;

class AuthController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            /** 
             * Checks oauth2 credentions and try to perform OAuth2 authorization on logged user.
             * AuthorizeFilter uses session to store incoming oauth2 request, so 
             * you can do additional steps, such as third party oauth authorization (Facebook, Google ...)  
             */
            'oauth2Auth' => [
                'class' => \minhhoit\oauth2\AuthorizeFilter::className(),
                'only' => ['index'],
            ],
        ];
    }
    public function actions()
    {
        return [
            /**
             * Returns an access token.
             */
            'token' => [
                'class' => \minhhoit\oauth2\TokenAction::classname(),
            ],
            /**
             * OPTIONAL
             * Third party oauth providers also can be used.
             */
            'back' => [
                'class' => \yii\authclient\AuthAction::className(),
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }
    /**
     * Display login form, signup or something else.
     * AuthClients such as Google also may be used
     */
    public function actionIndex()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            if ($this->isOauthRequest) {
                $this->finishAuthorization();
            } else {
                return $this->goBack();
            }
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
    /**
     * OPTIONAL
     * Third party oauth callback sample
     * @param OAuth2 $client
     */
    public function successCallback($client)
    {
        switch ($client::className()) {
            case GoogleOAuth::className():
                // Do login with automatic signup                
                break;
            ...
            default:
                break;
        }
        /**
         * If user is logged on, redirects to oauth client with success,
         * or redirects error with Access Denied
         */
        if ($this->isOauthRequest) {
            $this->finishAuthorization();
        }
    }
    
}
```
Api controller sample
```php
class ApiController extends \yii\rest\Controller
{
    public function behaviors()
    {
        return [
            /** 
             * Performs authorization by token
             */
            'tokenAuth' => [
                'class' => \minhhoit\oauth2\TokenAuth::className(),
            ],
        ];
    }
    /**
     * Returns username and email
     */
    public function actionIndex()
    {
        $user = \Yii::$app->user->identity;
        return [
            'username' => $user->username,
            'email'    =>  $user->email,
        ];
    }
}
```
Sample client config
```php
return [
...
   'components' => [
       'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'myserver' => [
                    'class'        => 'yii\authclient\OAuth2',
                    'clientId'     => 'unique client_id',
                    'clientSecret' => 'client_secret',
                    'tokenUrl'     => 'http://127.0.0.1/auth/token',
                    'authUrl'      => 'http://127.0.0.1/auth/index',
                    'apiBaseUrl'   => 'http://127.0.0.1/api',
                ],
            ],
        ],
];
```

## License

**conquer/oauth2** is released under the MIT License. See the bundled `LICENSE` for details.
