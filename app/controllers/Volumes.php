<?php
class Volumes extends Controller
{
  public function __construct()
  {
    /* if(!isLoggedIn()){
        redirect('users/login');
      } */

    $this->volumeModel = $this->model('Volume');
    $this->userModel = $this->model('User');
  }

  public function index()
  {
    // Get volumes
    $volumes = $this->volumeModel->getVolumes();

    $data = [
      'volumes' => $volumes
    ];

    $this->view('volumes/index', $data);
  }

  public function add($form_id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'price' => trim($_POST['price']),
        'user_id' => $_SESSION['user_id'],
        'form_id' => $form_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => ''
      ];

      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
      ) {
        // Validated
        if ($this->volumeModel->addVolume($data)) {
          flash('volume_message', 'Volume Added');
          redirect('volumes');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('volumes/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
        'price' => 0.00,
        'form_id' => $form_id,
        'title_err' => '',
        'body_err' => '',
        'price_err' => ''
      ];

      $this->view('volumes/add', $data);
    }
  }

  public function edit($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST array
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'price' => trim($_POST['price']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => '',
        'price_err' => ''
      ];

      // Make sure no errors
      if (
        empty($data['title_err']) && empty($data['body_err'])  && empty($data['price_err'])
      ) {
        // Validated
        if ($this->volumeModel->updateVolume($data)) {
          flash('volume_message', 'Volume Updated');
          redirect('volumes');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('volumes/edit', $data);
      }
    } else {
      // Get existing volume from model
      $volume = $this->volumeModel->getVolumeById($id);

      // Check for owner
      if ($volume->user_id != $_SESSION['user_id']) {
        redirect('volumes');
      }

      $data = [
        'id' => $id,
        'title' => $volume->title,
        'body' => $volume->body,
        'price' => $volume->price
      ];

      $this->view('volumes/edit', $data);
    }
  }

  public function show($id)
  {
    $volume = $this->volumeModel->getVolumeById($id);
    $user = $this->userModel->getUserById($volume->user_id);

    $data = [
      'volume' => $volume,
      'user' => $user
    ];

    $this->view('volumes/show', $data);
  }

  public function delete($id)
  {
    if (!isLoggedIn()) {
      redirect('users/login');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get existing volume from model
      $volume = $this->volumeModel->getVolumeById($id);

      // Check for owner
      if ($volume->user_id != $_SESSION['user_id']) {
        redirect('volumes');
      }

      if ($this->volumeModel->deleteVolume($id)) {
        flash('volume_message', 'Volume Removed');
        redirect('volumes');
      } else {
        die('Something went wrong');
      }
    } else {
      redirect('volumes');
    }
  }
}
