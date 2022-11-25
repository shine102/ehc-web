<?php
require './function.php';
//declare use session
session_start();

//declare utf-8 
header('Content-Type: text/html; charset=UTF-8');

//handle login

if (isset($_POST['login'])) {

    connectDB();

    //get input
    $username = $_POST['username'];
    $password = hash('sha256', isset($_POST['password']) ? $_POST['password'] : "");

    //check enter password and username
    if (!$username || !$password) {
        $message_error = "Please enter the full login username and password!";
        echo "<script type='text/javascript'>alert('$message_error');</script>";
        exit;
    }

    $regex1 = preg_match('/[\'"^£$%&*()}{@#~?><>,|=+¬-]/', $username);
    $regex2 = preg_match('/[\'"^£$%&*()}{@#~?><>,|=+¬-]/', $password);
    if (!$regex1 && !$regex2) {

        $query = "SELECT id,username,password FROM users WHERE username = ? and password = ?";

        // use prepared statement to prevent SQL injection
        $preparedStatement = $conn->prepare($query);
        $preparedStatement->bind_param('ss', $username, $password);
        $preparedStatement->execute();
        $result = $preparedStatement->get_result();

        if (mysqli_num_rows($result) <= 0) {
            $message = "Incorrect username or password!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        } else {
            //lưu tên đăng nhập
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
                
//            $score = get_score_byID($user_id);
//            if ($score === 3500) {
//                header("location: chall8_master");
//            } else {
//                header("location: challenges");
//            }
            header("location: challenges.php");
        }
    } else {
        http_response_code(400);
        die('Error processing bad or malformed request');
    }
}
disconnect_db();
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - EHC Workshop</title>
        <link href="http://fonts.cdnfonts.com/css/audiowide" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
              integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="./css/style.css">
        <title>Crescent</title>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#02bacd',
                        }
                    }
                }
            }
        </script>
    </head>

    <body>

        <div id="main" class="bg-black h-[100vh] w-[100vw]">
            <div class="flex flex-col items-center py-10">
                <h1 class="text-black hidden md:block md:text-[3rem]">Ethical Hackers Club</h1>
                <img src="./assets/logo.png" alt="logo" class="h-[150px]">

                <form method="POST" class="pt-5">
                    <div class="py-1">
                        <span class="text-white">Username</span>
                        <input type="text" name="username" id="username"
                               class="block text-center bg-transparent text-white font-medium border border-white border-[2px] rounded-2xl px-3 py-2 mt-2 md:w-[350px]">
                    </div>
                    <div class="py-1">
                        <span class="text-white">Password</span>
                        <input type="password" name="password" id="password"
                               class="block text-center bg-transparent text-white font-medium border border-white border-[2px] rounded-2xl px-3 py-2 mt-2 md:w-[350px]">
                    </div>
                    <div class="text-center pt-5">
                        <input type="submit" name="login" value="Login"
                               class="border border-[2px] border-white px-4 py-2 rounded-full text-white hover:text-black hover:bg-gray-400 cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
    </body>

</html>
