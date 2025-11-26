<?php
include("dbconnect.php");

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
    $result = mysqli_query($conn, "SELECT * FROM candidates WHERE election_id='$election_id'");
    $candidates = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $candidates[] = $row;
    }

    echo json_encode($candidates);
}
?>
