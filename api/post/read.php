<?php
// service

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include "../../config/Database.php";
include "../../models/Post.php";

// create a new instance of Database
$database = new Database();
$db = $database->connect();

// create a new instance of Post
$post = new Post($db);
$result = $post->read_post();
$num_of_rows = $result->rowCount();
if($num_of_rows > 0) {
    // create an array
    $post_array = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => $body,
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
        );

        // push data
        array_push($post_array, $post_item);
    }

    // convert to json and output
    echo json_encode($post_array);
} else {
    echo json_encode(
        array('message' => 'No post found')
    );
}