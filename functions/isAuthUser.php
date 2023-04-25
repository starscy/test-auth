<?php
function isAuthUser()
{
    return !empty($_SESSION['user']);
}