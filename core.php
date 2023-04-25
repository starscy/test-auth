<?php
require $_SERVER['DOCUMENT_ROOT'] . '/DataBase.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/isAuthUser.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/UserProvider.php';
include $_SERVER['DOCUMENT_ROOT'] . '/exceptions/EmailExistsException.php';

session_start();


