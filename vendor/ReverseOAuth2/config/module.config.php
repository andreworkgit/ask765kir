<?php

return array(
    
    'service_manager' => array(
        
        'factories' => array(
            
            'ReverseOAuth2\Google' => function ($sm) {
                $me = new \ReverseOAuth2\Client\Google;
                $cf = $sm->get('Config');
                $me->setOptions(new \ReverseOAuth2\ClientOptions($cf['reverseoauth2']['google']));
                return $me;
            },
            
            'ReverseOAuth2\LinkedIn' => function ($sm) {
                $me = new \ReverseOAuth2\Client\LinkedIn;
                $cf = $sm->get('Config');
                $me->setOptions(new \ReverseOAuth2\ClientOptions($cf['reverseoauth2']['linkedin']));
                return $me;
            },

            'ReverseOAuth2\Github' => function ($sm) {
                $me = new \ReverseOAuth2\Client\Github;
                $cf = $sm->get('Config');
                $me->setOptions(new \ReverseOAuth2\ClientOptions($cf['reverseoauth2']['github']));
                return $me;
            },
            
            'ReverseOAuth2\Facebook' => function ($sm) {
                $me = new \ReverseOAuth2\Client\Facebook;
                $cf = $sm->get('Config');
                $me->setOptions(new \ReverseOAuth2\ClientOptions($cf['reverseoauth2']['facebook']));
                return $me;
            }
            
        ),
        
        'invokables' => array(
            'ReverseOAuth2\Auth\Adapter' => 'ReverseOAuth2\Authentication\Adapter\ReverseOAuth2',  
        ),
        
    ),
    
    'reverseoauth2' => array(
        
        'google' => array(
            'scope' => array(
                'https://www.googleapis.com/auth/userinfo.profile',
                'https://www.googleapis.com/auth/userinfo.email'   
             ),
            'auth_uri'      => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri'     => 'https://accounts.google.com/o/oauth2/token',
            'info_uri'      => 'https://www.googleapis.com/oauth2/v1/userinfo',
            'client_id'     => 'your id',
            'client_secret' => 'your secret',
            'redirect_uri'  => 'your callback url which links to your controller',
        ),
        
        'facebook' => array(
            'scope' => array(
                /*
                'user_about_me',
                'user_activities',
                'user_birthday',
                'read_friendlists',
                //'...'
                */
             ),
            'auth_uri'      => 'https://www.facebook.com/dialog/oauth',
            'token_uri'     => 'https://graph.facebook.com/oauth/access_token',
            'info_uri'      => 'https://graph.facebook.com/me',
            'client_id'     => 'your id',
            'client_secret' => 'your secret',
            'redirect_uri'  => 'your callback url which links to your controller',
        ),
            
        'github' => array(
            'scope' => array(
                /*
                'user',
                'public_repo',
                'repo',
                'repo:status',
                'delete_repo',
                'gist'
                */
            ),
            'auth_uri'      => 'https://github.com/login/oauth/authorize',
            'token_uri'     => 'https://github.com/login/oauth/access_token',
            'info_uri'      => 'https://api.github.com/user',
            'client_id'     => 'your id',
            'client_secret' => 'your secret',
            'redirect_uri'  => 'your callback url which links to your controller',
        ),

        'linkedin' => array(
            'scope' => array(
                /*
                'user',
                'public_repo',
                'repo',
                'repo:status',
                'delete_repo',
                'gist'
                */
            ),
            'auth_uri'      => 'https://www.linkedin.com/uas/oauth2/authorization',
            'token_uri'     => 'https://www.linkedin.com/uas/oauth2/accessToken',
            'info_uri'      => 'https://api.linkedin.com/v1/people/~',
            'client_id'     => 'your api key',
            'client_secret' => 'your api secret',
            'redirect_uri'  => 'your callback url which links to your controller',
        ),

    )
    
);