<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Grpc\Auth;

/**
 */
class AuthClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Grpc\Auth\Request $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function auth(\Grpc\Auth\Request $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.auth.Auth/auth',
        $argument,
        ['\Grpc\Auth\Response', 'decode'],
        $metadata, $options);
    }

}
