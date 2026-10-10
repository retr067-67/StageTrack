<?php
require_once __DIR__ . '/config.php';

session_destroy();
setcookie(session_name(), '', time() - 3600);
redirect('index.php');
