<?php
// controller
class Post {
    private $conn;
    private $table = "posts";

    // Post Properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // get posts 
    public function read_post() {
        $sql = "
        SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.created_at FROM ". $this->table ." p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC;
        ";

        // prepare the statement
        $stmt = $this->conn->prepare($sql);

        // execute the query
        $stmt->execute();

        return $stmt;
    }
	// create posts 
    public function create_post() {
        $sql = "
            INSERT INTO ".$this->table." SET title = :title, body = :body, author = :author, category_id = :category_id
        ";

        $stmt = $this->conn->prepare($sql);

        // clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // bind parameter
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":body", $this->body);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":category_id", $this->category_id);

        if($stmt->execute()) {
            return true;
        }
        printf("Error: %s", $stmt->error);
        return false;
    }
}
?>