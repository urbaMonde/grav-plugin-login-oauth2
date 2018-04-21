<?php
namespace Grav\Plugin\Login\OAuth2\Providers;

use League\OAuth2\Client\Provider\Github;
use Grav\Common\Grav;

class GithubProvider extends BaseProvider
{
    protected $name = 'Github';
    protected $config;

    /** @var Github */
    protected $provider;

    public function __construct(array $options)
    {
        $this->config = Grav::instance()['config'];

        $options += [
            'clientId'      => $this->config->get('plugins.login-oauth2.providers.github.client_id'),
            'clientSecret'  => $this->config->get('plugins.login-oauth2.providers.github.client_secret'),
            'redirectUri'   => $this->config->get('plugins.login-oauth2.callback_uri'),
        ];

        parent::__construct($options);
    }

    public function getAuthorizationUrl()
    {
        $options = ['state' => $this->state];
        $options['scope'] = $this->config->get('plugins.login-oauth2.providers.github.options.scope');

        return $this->provider->getAuthorizationUrl($options);
    }

    public function getUserData($user)
    {
        $data = $user->toArray();

        $data_user = [
            'id'         => $user->getId(),
            'fullname'   => $user->getName(),
            'email'      => $user->getEmail(),
            'github'     => [
                'login'      => $data['login'],
                'location'   => $data['location'],
                'company'    => $data['company'],
                'avatar_url' => $data['avatar_url'],
            ]
        ];

        return $data_user;
    }
}