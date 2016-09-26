<?php
namespace minhhoit\oauth2;

use yii\web\Response;

/**
 * @author Andrew
 * @since 1.0
 */
class Exception extends \yii\base\UserException
{
    const ACCESS_DENIED             = 'access_denied';
    const INVALID_CLIENT            = 'invalid_client';
    const INVALID_GRANT             = 'invalid_grant';
    const INVALID_REQUEST           = 'invalid_request';
    const INVALID_SCOPE             = 'invalid_scope';
    const REDIRECT_URI_MISMATCH     = 'redirect_uri_mismatch';
    const SERVER_ERROR              = 'server_error';
    const TEMPORARILY_UNAVAILABLE   = 'temporarily_unavailable';
    const UNAUTHORIZED_CLIENT       = 'unauthorized_client';
    const UNSUPPORTED_GRANT_TYPE    = 'unsupported_grant_type';
    const UNSUPPORTED_RESPONSE_TYPE = 'unsupported_response_type';
    
    const NOT_IMPLEMENTED           = 'not_implemented';

    protected $error;

    /**
     * Constructor.
     * @param string $error_description (optional)
     * @param string $error A single error code
     * @param string $name error name
     */
    public function __construct($error_description = null, $error = self::INVALID_REQUEST)
    {
        $this->error = $error;
        parent::__construct($error_description, 0, null);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return isset($this->error) ? $this->error : self::SERVER_ERROR;
    }
}
