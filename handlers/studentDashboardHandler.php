<?php
session_start();

require_once __DIR__ . "./../helper/auth.php";

requireLogin();
requireRole('student');
