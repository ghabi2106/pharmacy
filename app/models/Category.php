<?php
  class Category {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    /* public function getCategories(){
      $this->db->query('SELECT *,
                        categories.id as categoryId,
                        users.id as userId,
                        categories.created_at as categoryCreated,
                        users.created_at as userCreated
                        FROM categories
                        INNER JOIN users
                        ON categories.user_id = users.id
                        ORDER BY categories.created_at DESC
                        ');

      $results = $this->db->resultSet();

      return $results;
    } */

    public function getCategories(){
      $this->db->query('SELECT *
                        FROM categories
                        ORDER BY categories.created_at DESC
                        ');

      $results = $this->db->resultSet();

      return $results;
    }

    public function addCategory($data){
      $this->db->query('INSERT INTO categories (title, user_id, body, img) VALUES(:title, :user_id, :body, :img)');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':img', $data['img']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateCategory($data){
      $this->db->query('UPDATE categories SET title = :title, body = :body, img = :img WHERE id = :id');
      // Bind values
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':img', $data['img']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getCategoryById($id){
      $this->db->query('SELECT * FROM categories WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function deleteCategory($id){
      $this->db->query('DELETE FROM categories WHERE id = :id');
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