<?php

session_start();

if (isset($_SESSION['id'])) {
    return ['./views/main.phtml', ['errorMessages']];
} else {
    header("Location: /signin");
}



