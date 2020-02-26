<?php
  class Form {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getForms(){
      $this->db->query('SELECT * FROM forms  ORDER BY forms.created_at DESC');

      $results = $this->db->resultSet();

      return $results;
    }

    public function addForm($data){
      $this->db->query('INSERT INTO forms (title, pharmaceutical_id, body, price, img) 
        VALUES(:title, :user_id, :pharmaceutical_id, :body, :price)');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':pharmaceutical_id', $data['pharmaceutical_id']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':img', $data['img']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateForm($data){
      if($data['img'] != null) {
        $this->db->query('UPDATE forms SET title = :title, body = :body, price = :price, img = :img, 
          WHERE id = :id');
        $this->db->bind(':img', $data['img']);
      } else
        $this->db->query('UPDATE forms SET title = :title, body = :body, price = :price, WHERE id = :id');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':price', $data['price']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getFormById($id){
      $this->db->query('SELECT * FROM forms WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function getFormsBypharmaceuticalId($pharmaceutical_id){
      $this->db->query('SELECT * FROM forms WHERE pharmaceutical_id = :pharmaceutical_id');
      $this->db->bind(':pharmaceutical_id', $pharmaceutical_id);

      $results = $this->db->resultSet();

      return $results;
    }

    public function deleteForm($id){
      $this->db->query('DELETE FROM forms WHERE id = :id');
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