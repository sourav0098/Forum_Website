<?php
session_start();
include "_dbconnect.php";

echo '    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
    <a class="navbar-brand" href="./index.php">iDiscuss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./about.php">About</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Top Categories
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

                $sql="SELECT category_id,category_name FROM `categories` LIMIT 3";
                $result=mysqli_query($conn,$sql);
                while($row=mysqli_fetch_assoc($result)){   
                    echo '<li><a class="dropdown-item" href="/php/forumWebsite/threadlist.php?catid='.$row['category_id'].'">'.$row['category_name'].'</a></li>';
                }
                echo '</ul>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./contact.php">Contact Us</a>
            </li>
        </ul>
        <div class="d-flex flex-row">
        <form class="d-flex" role="search" method="GET" action="search.php">
        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
        </form>';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    echo '<h5 style="margin:auto 0 auto 1rem;" class="text-white">Welcome, ' . $_SESSION['name'] . '</h5>
    <a href="/php/forumWebsite/partials/_logout.php" class="btn btn-primary ms-3">Logout</a>';
    ;
}

if (!isset($_SESSION['loggedin'])) { 
    echo '<button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
        <button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#signupModal">SignUp</button>';
}
echo '</div>
    </div>
</div>
</nav>';

include "./partials/_loginModal.php";
include "./partials/_signupModal.php";

try {
    if (isset($_GET['signup']) && $_GET['signup'] == "true") {
        echo '<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
        <strong>Success!</strong> Signed Up successfully. You can login now
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }

    if (isset($_GET['emailExist']) && $_GET['emailExist'] == true) {
        echo '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
        <strong>Error!</strong> Email already exists
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }

    if (isset($_GET['passwordMismatch']) && $_GET['passwordMismatch'] == true) {
        echo '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
        <strong>Error!</strong> Passwords does not match
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }

    if (isset($_GET['login']) && $_GET['login'] == "false") {
        echo '<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
        <strong>Error!</strong> Unable to login Please enter valid credentials
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }

} catch (Exception $e) {
}
