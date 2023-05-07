<?php

session_start();

if (isset($_SESSION['id'])) {
    return ['./views/main.phtml', ['errorMessages'], true];
} else {
    header("Location: /signin");
}



