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

        .card img {
            width: 250px;
            object-fit: cover;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <?php include "partials/_dbconnect.php" ?>
    <?php include "partials/header.php" ?>

    <!-- Add Full Text Search in MySQL
    alter table threads add FULLTEXT(`thread_title`,`description`) 

    select * from threads where match(`thread_title`,`description`) against('not able')-->

    <!-- Search Results starts here -->
    <div class="container min-vh-100">
        <h2 class="py-2">Search Results for "<em><?php echo $_GET['search'] ?></em>"</h2>

        <?php
        $sql = "SELECT * FROM threads WHERE match(`thread_title`,`description`) against('" . $_GET['search'] . "')";
        $result = mysqli_query($conn, $sql);
        $noResults=true;
        while ($row = mysqli_fetch_assoc($result)) {
            $thread_id = $row['thread_id'];
            $title = $row['thread_title'];
            $description = $row['description'];
            $noResults=false;

            // Results
            echo '<div class="d-flex my-3">
                    <div class="flex-grow-1">
                        <h5><a class="text-black text-decoration-none" href="thread.php?threadid=' . $thread_id . '">' . $title . '</a></h5>
                        <p>' . $description . '</p>
                    </div>
                </div>';
        }
        if ($noResults) {
            echo '<div class="container-fluid bg-light p-5">
            <h1 class="display-4">No Results Found</h1>
            <p class="lead">Suggestions:</p>
            <ul>
                <li>Make sure that all words are spelled correctly.</li>
                <li>Try different keywords.</li>
                <li>Try more general keywords.</li>
            </ul>
        </div>';
        }
        ?>


    </div>

    <?php include "partials/footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>