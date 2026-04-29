<?php
session_start();

require_once __DIR__ . "./../../helper/AuthHelper.php";

AuthHelper::requireLogin();
AuthHelper::requireRole('student');
