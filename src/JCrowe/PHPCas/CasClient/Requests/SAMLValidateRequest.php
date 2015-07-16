<?php
/**
 * Created by PhpStorm.
 * User: jcrowe
 * Date: 7/15/15
 * Time: 4:18 PM
 */

namespace JCrowe\PHPCas\CasClient\Requests;


class SAMLValidateRequest extends AbstractRequest {


    /**
     * URL encoded service identifier of the back-end service.
     *
     * Note that as an HTTP request parameter, this URL value MUST be
     * URL-encoded as described in Section 2.2 of RFC 1738[4]. The service
     * identifier specified here MUST match the service parameter provided
     * to /login. See Section 2.1.1. The TARGET service SHALL use HTTPS.
     * SAML attributes MUST NOT be released to a non-SSL site.
     *
     * @var string
     */
    protected $target;



    public function __construct($target)
    {
        if ($this->isValidTarget($target)) {

            $this->target = $target;
        } else {

            // throw an exception, non secure!
        }
    }


    /**
     * Verify that the target is being sent over https
     *
     * @param $target
     * @return bool
     */
    public function isValidTarget($target)
    {
        return strpos($target, 'https://') === 0;
    }

}