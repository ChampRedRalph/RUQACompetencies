<?php
// Include the database connection
include '../adminquarterlyassessment/roxcon.php'; // Adjust the path to your connection file

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Retrieve form data
    $schoolid = $_GET['schoolid'];
    $studname = $_GET['name'];
    $gradelevel = (int)$_GET['grade'];
    $subject = $_GET['subject'];
    
    // Determine how many answers to collect based on grade level
    if ($gradelevel >= 1 && $gradelevel <= 3) {
        $maxAnswers = 30;
    } elseif ($gradelevel >= 4 && $gradelevel <= 6) {
        $maxAnswers = 40;
    } else {
        $maxAnswers = 50;
    }

    // Prepare an array to store answers
    $answers = [];
    
    // Loop through answers (q1 to qN) based on grade level but store as a1 to aN in the database
    for ($i = 1; $i <= $maxAnswers; $i++) {
        $answerKey = 'question' . $i; // e.g., q1, q2, etc. (from form inputs)
        $answers[$i] = isset($_GET[$answerKey]) ? $_GET[$answerKey] : null;
    }

    // Create the SQL query dynamically to insert the answers
    $sql = "INSERT INTO tb_competencies (studname, gradelevel, subject";
    
    // Add dynamic answer fields (c1 to cN)
    for ($i = 1; $i <= $maxAnswers; $i++) {
        $sql .= ", c$i";
    }
    
    $sql .= ") VALUES ('$studname', '$gradelevel', '$subject'";
    
    // Add dynamic answer values (c1 to cN)
    for ($i = 1; $i <= $maxAnswers; $i++) {
        $sql .= ", '" . $answers[$i] . "'";
    }
    
    $sql .= ")";
    //echo $sql;

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Your answers are recorded successfully. Proceed to exam page.');
            window.location.href = 'index.php';
          </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    // Close the connection
    $conn->close();
}
?>