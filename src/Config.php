<?php

namespace src;

require_once __DIR__ . '/Env.php';

class Config
{
    const BASE_DIR = BASE_DIR;
    const TOKEN_JV = TOKEN_JV;

    const DB_DRIVER = DB_DRIVER;
    const DB_HOST = DB_HOST;
    const DB_DATABASE = DB_DATABASE;
    const DB_USER = DB_USER;
    const DB_PASS = DB_PASS;

    const SEFAZ_ENVIRONMENT = SEFAZ_ENVIRONMENT;

    const FRONT_URL = FRONT_URL;

    const EMAIL_API = EMAIL_API;
    const SENHA_EMAIL_API = SENHA_EMAIL_API;
    const SMTP_PORT = SMTP_PORT;
    const SMTP_HOST = SMTP_HOST;

    const API_CIELO_LIO_TESTE = API_CIELO_LIO_TESTE;
    const API_CIELO_LIO = API_CIELO_LIO;
    const API_CIELO_LINK = API_CIELO_LINK;
    const API_CIELO_TK = API_CIELO_TK;

    const API_MELHOR_ENVIO = API_MELHOR_ENVIO;
    const TOKEN_MELHOR_ENVIO = TOKEN_MELHOR_ENVIO;

    const API_EREDE_SANDBOX = API_EREDE_SANDBOX;
    const API_EREDE         = API_EREDE;


    const OPENAI_API_KEY = OPENAI_API_KEY;
    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';
}
