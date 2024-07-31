<?php
	//If the user is not logged in & remember me cookie exists
	if(!isset($_SESSION['user_id']) && !empty($_COOKIE['rememberme'])){
		//array_key_exists('user_id', $_SESSION);
		// f1: COOKIE: $a . "," . bin2hex($b)
		// f2: hash('sha256', $a)
		
		// Extract the $authentificator 1&2 from the cookie
		list($authentificator1, $authentificator2) = explode(',', $_COOKIE['rememberme']);
		
		$authentificator2 = hex2bin($authentificator2);
		
		$f2authentificator2 = hash('sha256', $authentificator2);
		
		
		//Look for authentificator1 in a rememberme table.
		$sql = "SELECT * FROM rememberme WHERE authentificator1 = '$authentificator1'";
		$result = mysqli_query($link, $sql);
		
		if(!$result){
			echo '<div class="alert alert-danger">There was an error running the query.</div>';
			exit;
		}
		$count = mysqli_num_rows($result);
		if($count !== 1){
            echo '<div class="alert alert-danger">Remember me process failed!</div>';
			exit;
        }
		
		// if authentificator2 doesn't match
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if(!hash_equals($row['f2authentificator2'], $f2authentificator2)){
            echo '<div class="alert alert-danger">hash_equals returned false.</div>';
		}
		else{
			// generate new authentificator
			// store them in a cookie and rememberme table.
			
			// Create two variables $authentificator1 and $authentificator2
                $authentificator1 = bin2hex(openssl_random_pseudo_bytes(10));
                $authentificator2 = openssl_random_pseudo_bytes(20);

                // Store them in a cookie
                function f1($a, $b){
                    $c = $a . "," . bin2hex($b);
                    return $c;
                }
                $cookieValue = f1($authentificator1, $authentificator2);

                setcookie(
                    "rememberme",
                    $cookieValue,
                    time() + 1296000
                );

                // Run query to score them in rememberme table
                function f2($a){
                    $b = hash('sha256', $a);
                    return $b;
                }
                $f2authentificator2 = f2($authentificator2);
                $user_id = $_SESSION["user_id"];
                $expiration = date('Y-m-d H:i:s',time() + 1296000);

                $sql = "INSERT INTO rememberme (authentificator1, f2authentificator2, user_id, expires)
                                        VALUES('$authentificator1', '$f2authentificator2', '$user_id', '$expiration')";

                $result = mysqli_query($link, $sql);

                if(!$result){
                    echo '<div class="alert alert-danger">There was an error storing data to remeber you next time.</div>';
                    //echo '<div class="alert alert-danger">' . mysqli_error($link) . '</div>';
                }
			// Log the user in and redirect to notes page
			$_SESSION['user_id'] = $row['user_id'];
			header("location:mainpageloggedin.php");
			
		}
	}
	//else{
        //echo '<div class="alert alert-danger" style="margin-top:50px;">User_id:' . $_SESSION['user_id'] . '</div>';
        //echo '<div class="alert alert-danger">User_id:' . $_COOKIE['rememberme'] . '</div>';
	//}
?>