<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
$config['api_helper_enabled']=TRUE;
$config['jwt_secret']=getenv('JWT_SECRET')?:'';
$config['refresh_token_key']=getenv('REFRESH_TOKEN_KEY')?:'';
$config['payload_token_expiration']=900;
$config['refresh_token_expiration']=604800;
$config['jwt_verify_user']=TRUE;
$config['users_table']='users';
$config['refresh_token_table']='refresh_tokens';
$config['jwt_issuer']='puyo-lab6-api';
$config['jwt_audience']='puyo-lab6-react';
$config['allow_origin']=array_values(array_filter(array_map('trim',explode(',',getenv('FRONTEND_ORIGIN')?:'http://localhost:5173,http://127.0.0.1:5173'))));
$config['rate_limit_enabled']=TRUE;
$config['rate_limit_requests']=120;
$config['rate_limit_seconds']=60;