<?php

require_once('config.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'books') {
    include("books.php");
} else {
    include("home.php");
}

include("footer.php");
