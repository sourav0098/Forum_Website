<?php 
    include "./_dbconnect.php";
    $emailExist=true;
    $passwordMismatch=false;
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        
        $email=$_POST['email'];
        $name=$_POST['name'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];

        $existSql="SELECT * from `users` where email='$email'";
        $result=mysqli_query($conn,$existSql);

        $numRows=mysqli_num_rows($result);
        if($numRows>0){
            $emailExist=true;
        }
        else{
            $emailExist=false;
            if($password==$cpassword){
                $passwordMismatch=false;
                $hash=password_hash($password,PASSWORD_DEFAULT);
                $sql="INSERT INTO `users` (`email`, `name`, `password`) VALUES ('$email', '$name', '$hash')";
                $result=mysqli_query($conn,$sql);
                if($result){
                    header("location:/php/forumWebsite/index.php?signup=true");
                    exit();
                }
            }
            else{
                $passwordMismatch=true;
            }
        }
    }
    header("location:/php/forumWebsite/index.php?signup=false&emailExist=$emailExist&passwordMismatch=$passwordMismatch");
?>