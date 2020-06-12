<?php

namespace App\Service;

use mysql_xdevapi\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiService
{
    const BASE_URL = 'https://api.mobicoop.io';

    private $session;
    private $container;

    public function __construct(SessionInterface $session, ContainerInterface $container)
    {
        $this->session = $session;
        $this->container = $container;
    }

    public function getToken()
    {
        $client = HttpClient::create();
        $username = $this->container->getParameter('mobicoop_user');
        $password = $this->container->getParameter('mobicoop_password');
        $response = $client->request('POST', self::BASE_URL . '/auth', [
            'json' => ['username' => $username, 'password' => $password]
        ]);
        $content = $response->getContent();
        $allToken = json_decode($content);
        $token = $allToken->{'token'};
        $refreshToken = $allToken->{'refreshToken'};
        $this->session->set('token', $token);
        $this->session->set('refreshToken', $refreshToken);
    }

    public function baseUri()
    {
        $client = HttpClient::createForBaseUri(self::BASE_URL, [
            // HTTP Bearer authentication (also called token authentication)
            'auth_bearer' => $this->session->get('token'),
        ]);
        return $client;
    }

    public function getUser($form)
    {
        $client = $this->baseUri();
        $response = $client->request('GET', '/users', [
            'query' => [
                'email' => $form->getData()['email']
            ]
        ]);
        return json_decode($response->getContent(), true);
    }

    public function passwordVerify($user, $password)
    {
        $passwordUser = $user['hydra:member'][0]['password'];
        return password_verify($password, $passwordUser);
    }

    public static function addPhoneDisplay($array)
    {
        $array['phoneDisplay'] = 1;
        return $array;
    }
}