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
    $id = $_GET['threadid'];
    $sql = "SELECT * FROM `threads` where thread_id=$id";
    $result = mysqli_query($conn, $sql);


    while ($row = mysqli_fetch_assoc($result)) {
        $thread_title = $row['thread_title'];
        $description = $row['description'];
        $postedBy = $row['user_id'];

        $sql2 = "SELECT name from `users` where user_id=$postedBy";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
    }
    ?>

    <!-- To insert comments in DB -->
    <?php
    $showAlert = false;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $th_comment = $_POST['comment'];
        $user_id = $_POST['user_id'];

        // Sanatizing javascript tags in SQL
        $th_comment=str_replace('<','&lt;',$th_comment);
        $th_comment=str_replace('>','&gt;',$th_comment);

        $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`,`user_id`) VALUES ('$th_comment', '$id','$user_id')";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Your comment has been added successfully
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        }
    }


    ?>

    <div class="container my-4">
        <div class="container-fluid bg-light p-5">
            <h1 class="display-4"><?php echo $thread_title ?></h1>
            <p class="lead"><?php echo $description ?></p>
            <hr class="my-4">
            <p>
                No Spam / Advertising / Self-promote in the forums, Do not post copyright-infringing material. Do not post “offensive” posts, links or images. Do not cross post questions. Remain respectful of other members at all times
            </p>
            <p><strong>Posted By: </strong><?php echo $row2['name']?></p>
        </div>
    </div>
    <?php
    if (isset($_SESSION['loggedin'])) {
        echo '<div class="container">
            <h1 class="py-2">Post a Comment</h1>
            <!-- <form action="/php/forumWebsite/threadlist.php?catid=".$id method="POST"> -->
    
            <!--  1) PHP_SELF       Returns the request path excluding querystring
                  2)REQUEST_URI     Holds the full request path including the querystring-->
    
            <form action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
                <div class="mb-3">
                    <label for="comment" class="form-label">Type your comment</label>
                    <textarea class="form-control" id="comment" name="comment" style="height: 100px"></textarea>
                    <input type="hidden" value="' . $_SESSION['user_id'] . '" name="user_id">

                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>';
    } else {
        echo '<div class="container">
            <h1 class="py-2">Ask a Question</h1>
            <p class="lead">You are not logged in. Please login to post a comment</p>
        </div>';
    }
    ?>

    <div class="container">
        <h1 class="py-2">Discussions</h1>
        <?php

        $id = $_GET['threadid'];
        $sql = "SELECT * FROM `comments` where thread_id=$id  ORDER BY comment_time DESC";
        $result = mysqli_query($conn, $sql);

        $noRecord = true;

        while ($row = mysqli_fetch_assoc($result)) {
            $noRecord = false;
            $comment_id = $row['comment_id'];
            $comment_content = $row['comment_content'];
            $comment_time = $row['comment_time'];
            $postedTime = date('F d, Y h:m A', strtotime($row['comment_time']));

            $user_id = $row['user_id'];

            $sql2 = "SELECT name from `users` where user_id=$user_id";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);


            echo '<div class="d-flex my-3">
                <div class="flex-shrink-0">
                    <img src="./images/user.png" width="40px" alt="...">
                </div>
                <div class="flex-grow-1 ms-3">
                    <p style="margin:0;"><strong>' . $row2['name'] . '</strong></p>
                    <p style="margin:0;">' . $postedTime . '</p>
                    <p>' . $comment_content . '</p>
                </div>
            </div>';
        }

        if ($noRecord) {
            echo '<div class="container-fluid bg-light p-5">
                <h1 class="display-4">No Comments Found</h2>
                <h2 class="lead">Be the first person to start a discussion</h2>
            </div>';
        }

        ?>


        <!-- Threads -->
    </div>
    <?php include "partials/footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>