<?php

include("dbconfig.php");
// echo "hello";

if($_SERVER['REQUEST_METHOD'] === "POST"){
    if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE email='" . $email . "' AND pass='" . $password ."';";
    // echo $query;
    $req = mysqli_query($conn,$query);
    $data = mysqli_fetch_assoc($req);
    // print_r($data);
    if ($data == NULL) {
        echo "<script>alert('your email and password is wronge ');</script>";
        exit();
    }

    if($data["email"] == $email && $data["pass"] == $password){

            session_start();
            $_SESSION["user_id"] = $data["id"];
            $_SESSION["name"] = $data["name"];
            $_SESSION["email"] = $data["email"];
            $_SESSION["password"] = $data["pass"];
            $_SESSION['logged_in'] = true;
            header("Location: index.php");
            exit();
   
   }else{
      echo "<script>alert('your username and password is wronge ');</script>";
      header("Location: index.php");
      exit();
   }
    }
    elseif (isset($_POST['signup'])) {
        $name =$_POST['name'];
    $email =$_POST['email'];
    $phone = $_POST['ph'];
    $password2 = $_POST['pass2'];
    if($password1 != $password2){
        die("password not match");
    }

        $check = "SELECT email FROM user WHERE email='$email'";
        $res = mysqli_query($conn,$check);
        $row = mysqli_num_rows($res);
        echo $row;
        if($row >= 1){
            echo "<script>alert('user & email alredy exits');</script>";
            header("Location:index.php");
        }
        else{
               
    $query = "INSERT INTO `user` (`name`, `email`, `pass`, `phone`) VALUES ('$name', '$email', '$password1', '$phone');";
    mysqli_query($conn,$query);
    echo "signup";
    // Database connection

    // echo $name . "<br>";
    // echo $email . "<br>";
    // echo $phone . "<br>";
    // echo $password1 . "<br>";
    // echo $password2 . "<br>";
    header("Location:index.php");


        }
 
    }
    else{
    header("Location:home1.php");
    }
}
else{
    header("Location:index.php");
}


?>