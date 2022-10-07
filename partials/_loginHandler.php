<?php
include "./_dbconnect.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $sql="SELECT * from `users` where email='$email'";
    $result=mysqli_query($conn,$sql);
    $numRows=mysqli_num_rows($result);

    if($numRows==1){
        $row=mysqli_fetch_assoc($result);
        if(password_verify($password,$row['password'])){
            session_start();
            $_SESSION['loggedin']=true;
            $_SESSION['email']=$email;
            $_SESSION['name']=$row['name'];
            $_SESSION['user_id']=$row['user_id'];
            header("location:/php/forumWebsite/index.php");
            exit();
        }
        else{
            header("location:/php/forumWebsite/index.php?login=false");
        }
    }
    header("location:/php/forumWebsite/index.php?login=false");
}
