<?php
  class Volume {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getVolumes(){
      $this->db->query('SELECT * FROM volumes  ORDER BY volumes.created_at DESC');

      $results = $this->db->resultSet();

      return $results;
    }

    public function addVolume($data){
      $this->db->query('INSERT INTO volumes (title, form_id, body, price) 
        VALUES(:title, :user_id, :form_id, :body, :price)');
      // Bind values
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':form_id', $data['form_id']);
      $this->db->bind(':body', $data['body']);
      $this->db->bind(':price', $data['price']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateVolume($data){
      $this->db->query('UPDATE volumes SET title = :title, body = :body, price = :price, WHERE id = :id');
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

    public function getVolumeById($id){
      $this->db->query('SELECT * FROM volumes WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function getVolumesByformId($form_id){
      $this->db->query('SELECT * FROM volumes WHERE form_id = :form_id');
      $this->db->bind(':form_id', $form_id);

      $results = $this->db->resultSet();

      return $results;
    }

    public function deleteVolume($id){
      $this->db->query('DELETE FROM volumes WHERE id = :id');
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