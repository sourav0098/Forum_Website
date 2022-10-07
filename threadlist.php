<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iDiscuss</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style>
        .carousel-item img {
            object-fit: cover;
            height: 450px;
        }
    </style>
</head>

<body>
    <?php include "partials/header.php" ?>
    <?php include "partials/_dbconnect.php" ?>

    <!-- Header For Each Category -->
    <?php
    $id = $_GET['catid'];
    $sql = "SELECT * FROM `categories` where category_id=$id";
    $result = mysqli_query($conn, $sql);


    while ($row = mysqli_fetch_assoc($result)) {
        $category_name = $row['category_name'];
        $description = $row['category_description'];
    }
    ?>

    <!-- Inserting questions in Database -->
    <?php
    $showAlert = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $th_title = $_POST['title'];
        $th_description = $_POST['thread-description'];
        $user_id = $_POST['user_id'];

        
        // Sanatizing javascript tags in SQL
        $th_description=str_replace('<','&lt;',$th_description);
        $th_description=str_replace('>','&gt;',$th_description);

        $sql = "INSERT INTO `threads` (`thread_title`, `description`,`user_id`, `category_id`) VALUES ('$th_title', '$th_description','$user_id' ,'$id')";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your thread has been added successfully. Please wait for the community to respond
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        }
    }


    ?>

    <div class="container my-4">
        <div class="container-fluid bg-light p-5">
            <h1 class="display-4">Welcome to <?php echo $category_name ?> Forums</h1>
            <p class="lead"><?php echo $description ?></p>
            <hr class="my-4">
            <p>
            <ul>
                <li>No Spam / Advertising / Self-promote in the forums</li>
                <li>Do not post copyright-infringing material</li>
                <li>Do not post “offensive” posts, links or images</li>
                <li>Do not cross post questions</li>
                <li>Remain respectful of other members at all times</li>
            </ul>
            </p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </div>
    </div>

    <?php
    try {
        if (isset($_SESSION['loggedin'])) {
            echo '<div class="container">
            <h1 class="py-2">Ask a Question</h1>
            <!-- <form action="/php/forumWebsite/threadlist.php?catid=".$id method="POST"> -->
        
            <!--  1) PHP_SELF       Returns the request path excluding querystring
                  2)REQUEST_URI     Holds the full request path including the querystring-->
        
            <form action="'. $_SERVER['REQUEST_URI'] .' " method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="thread-description" class="form-label">Tell us more</label>
                    <textarea class="form-control" id="thread-description" name="thread-description" style="height: 100px"></textarea>
                    <input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>';
        } else {
            echo '<div class="container">
            <h1 class="py-2">Ask a Question</h1>
            <p class="lead">You are not logged in. Please login to start a discussion</p>
        </div>';
        }
    } catch (Exception $e) {
    }

    ?>


    <div class="container">
        <h1 class="py-2">Browse Questions</h1>

        <!-- Threads -->
        <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` where category_id=$id ORDER BY timestamp desc";
        $result = mysqli_query($conn, $sql);

        $noRecord = true;


        while ($row = mysqli_fetch_assoc($result)) {
            $noRecord = false;
            $thread_id = $row['thread_id'];
            $thread_title = $row['thread_title'];
            $description = $row['description'];
            $postedTime = date('F d, Y h:m A', strtotime($row['timestamp']));
            $user_id = $row['user_id'];

            $sql2 = "SELECT name from `users` where user_id=$user_id";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            echo '<div class="d-flex my-3">
                <div class="flex-shrink-0">
                    <img src="./images/user.png" width="40px" alt="...">
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex justify-content-between">
                        <h5><a class="text-black text-decoration-none" href="thread.php?threadid=' . $thread_id . '">' . $thread_title . '</a></h5>
                        <p>' . $row2['name'] . ' ' . $postedTime . '</p>
                    </div>
                    <p>' . $description . '</p>
                </div>
            </div>';
        }

        if ($noRecord) {
            echo '
            <div class="container-fluid bg-light p-5">
                <h1 class="display-4">No Results Found</h2>
                <h2 class="lead">Be the first person to ask a question</h2>
            </div>';
        }

        ?>
    </div>
    <?php include "partials/footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>