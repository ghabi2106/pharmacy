<?php
  class Pharmaceutical {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getPharmaceuticals(){
      $this->db->query('SELECT *,
                        pharmaceuticals.id as pharmaceuticalId,
                        users.id as userId,
                        pharmaceuticals.created_at as pharmaceuticalCreated,
                        users.created_at as userCreated
                        FROM pharmaceuticals
                        INNER JOIN users
                        ON pharmaceuticals.user_id = users.id
                        ORDER BY pharmaceuticals.created_at DESC
                        ');

      $results = $this->db->resultSet();

      return $results;
    }

    public function addPharmaceutical($data){
      $this->db->query('INSERT INTO pharmaceuticals (title, user_id, category_id, body, price, popular, img, img1) 
        VALUES(:title, :user_id, :category_id, :body, :price, :popular, :img, :img1)');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':category_id', $data['category_id']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':popular', $data['popular']);
      $this->db->bind(':img', $data['img']);
      $this->db->bind(':img1', $data['img1']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updatePharmaceutical($data){
      if($data['img'] != null && $data['img1'] != null) {
        $this->db->query('UPDATE pharmaceuticals SET title = :title, body = :body, price = :price, img = :img, 
          img1 = :img1, popular = :popular WHERE id = :id');
        $this->db->bind(':img', $data['img']);
        $this->db->bind(':img1', $data['img1']);
      } else if($data['img'] != null) {
        $this->db->query('UPDATE pharmaceuticals SET title = :title, body = :body, price = :price, img = :img, 
          popular = :popular WHERE id = :id');
        $this->db->bind(':img', $data['img']);
      } else if($data['img1'] != null) {
        $this->db->query('UPDATE pharmaceuticals SET title = :title, body = :body, price = :price, 
          img1 = :img1, popular = :popular WHERE id = :id');
        $this->db->bind(':img1', $data['img1']);
      } else
        $this->db->query('UPDATE pharmaceuticals SET title = :title, body = :body, price = :price, 
          popular = :popular WHERE id = :id');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':popular', $data['popular']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getPharmaceuticalById($id){
      $this->db->query('SELECT * FROM pharmaceuticals WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function getPharmaceuticalsBycategoryId($category_id){
      $this->db->query('SELECT * FROM pharmaceuticals WHERE category_id = :category_id');
      $this->db->bind(':category_id', $category_id);

      $results = $this->db->resultSet();

      return $results;
    }

    public function get_search($category_id, $limit_num){
      $this->db->query('SELECT * FROM pharmaceuticals WHERE category_id = :category_id ' . $limit_num);
      $this->db->bind(':category_id', $category_id);

      $results = $this->db->resultSet();

      return $results;
    }

    public function get_search_count($category_id){
      $this->db->query('SELECT id FROM pharmaceuticals WHERE category_id = :category_id');
      $this->db->bind(':category_id', $category_id);

      $count = $this->db->rowCount();

      return $count;
    }

    public function getPharmaceuticalsPopular($limit_num){
      $this->db->query('SELECT * FROM pharmaceuticals WHERE popular = 1 ORDER BY created_at ASC LIMIT :limit_num');
      $this->db->bind(':limit_num', $limit_num);

      $results = $this->db->resultSet();

      return $results;
    }

    public function getPharmaceuticalsNew($limit_num){
      $this->db->query('SELECT * FROM pharmaceuticals ORDER BY created_at ASC LIMIT :limit_num');
      $this->db->bind(':limit_num', $limit_num);

      $results = $this->db->resultSet();

      return $results;
    }

    public function deletePharmaceutical($id){
      $this->db->query('DELETE FROM pharmaceuticals WHERE id = :id');
      // Bind values
      $this->db->bind(':id', $id);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }