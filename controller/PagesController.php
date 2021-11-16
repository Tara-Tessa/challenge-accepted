<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../dao/TipsDAO.php';
require_once __DIR__ . '/../dao/ReactiesDAO.php';
require_once __DIR__ . '/../dao/OpmerkingenDAO.php';

class PagesController extends Controller
{

  private $tipsDAO;
  private $reactiesDAO;
  private $opmerkingenDAO;

  function __construct()
  {
    $this->tipsDAO = new TipsDAO();
    $this->reactiesDAO = new ReactiesDAO();
    $this->opmerkingenDAO = new OpmerkingenDAO();
  }

  //construct maken

  public function index()
  {
    $popular = $this->reactiesDAO->sortByPopularLim3();
    $tips = [];
    foreach ($popular as $index => $popularId) {
      $tips[] = $this->tipsDAO->selectTipsById($popularId["tip_id"]);
    }
    $this->set('tips', $tips);
  }

  public function toevoegen()
  {
    // variabele om foutmelding bij te houden
    $error = '';

    // controleer of er iets in de $_POST zit
    if (!empty($_POST['action'])) {
      // controleer of het wel om het juiste formulier gaat
      if ($_POST['action'] == 'add-tip') {
        // controleren of er een bestand werd geselecteerd
        if (empty($_FILES['image']) || !empty($_FILES['image']['error'])) {
          $error = 'Gelieve een bestand te selecteren';
        }

        if (empty($error)) {
          // controleer of het een afbeelding is van het type jpg, png of gif
          $whitelist_type = array('image/jpeg', 'image/png', 'image/gif');
          if (!in_array($_FILES['image']['type'], $whitelist_type)) {
            $error = 'Gelieve een jpeg, png of gif te selecteren';
          }
        }

        if (empty($error)) {
          // controleer de afmetingen van het bestand: pas deze gerust aan voor je eigen project
          // width: 960
          // height: 450
          $size = getimagesize($_FILES['image']['tmp_name']);
        }

        if (empty($error)) {
          // map met een random naam aanmaken voor de upload: redelijk zeker dat er geen conflict is met andere uploads
          $projectFolder = realpath(__DIR__);
          $targetFolder = $projectFolder . '/../assets/uploads';
          $targetFolder = tempnam($targetFolder, '');
          unlink($targetFolder);
          mkdir($targetFolder, 0777, true);
          $targetFileName = $targetFolder . '/' . $_FILES['image']['name'];

          // via de functie _resizeAndCrop() de afbeelding croppen en resizen tot de gevraagde afmeting
          // pas gerust aan in je eigen project
          $this->_resizeAndCrop($_FILES['image']['tmp_name'], $targetFileName, 960, 450);
          $relativeFileName = substr($targetFileName, 1 + strlen($projectFolder));

          //ads weg
          $url = '';
          if (strpos($relativeFileName, 'ads') === 0) {
            $url = substr($relativeFileName, 4);
          }


          if (empty($_POST['auteur'])) {
            $_POST['auteur'] = 'Tante Kaat';
          }

          $data = array(
            'titel' => $_POST['titel'],
            'tag' => $_POST['tag'],
            'tip' => $_POST['tip'],
            'auteur' => $_POST['auteur'],
            'afbeelding' => $url,
          );

          // TODO: schrijf de afbeelding weg naar de database op basis van de array $data

          $nieuweTip = $this->tipsDAO->tipToevoegen($data);

          // TODO 2: zorg dat de variabele $error getoond wordt indien er iets fout gegaan is

          if (empty($nieuweTip)) {
            $errors = $this->tipsDAO->validate($data);
            $this->set('errors', $errors);
          } else {
            header('Location: index.php?page=tips');
            exit();
          }
        }
      }
    }
  }

  private function _resizeAndCrop($src, $dst, $thumb_width, $thumb_height)
  {
    $type = exif_imagetype($src);
    $allowedTypes = array(
      1,  // [] gif
      2,  // [] jpg
      3  // [] png
    );

    if (!in_array($type, $allowedTypes)) {
      return false;
    }

    switch ($type) {
      case 1:
        $image = imagecreatefromgif($src);
        break;
      case 2:
        $image = imagecreatefromjpeg($src);
        break;
      case 3:
        $image = imagecreatefrompng($src);
        break;
      case 6:
        $image = imagecreatefrombmp($src);
        break;
    }

    $filename = $dst;

    $width = imagesx($image);
    $height = imagesy($image);

    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

    if ($original_aspect >= $thumb_aspect) {
      // If image is wider than thumbnail (in aspect ratio sense)
      $new_height = $thumb_height;
      $new_width = $width / ($height / $thumb_height);
    } else {
      // If the thumbnail is wider than the image
      $new_width = $thumb_width;
      $new_height = $height / ($width / $thumb_width);
    }

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    // Resize and crop
    imagecopyresampled(
      $thumb,
      $image,
      0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
      0 - ($new_height - $thumb_height) / 2, // Center the image vertically
      0,
      0,
      $new_width,
      $new_height,
      $width,
      $height
    );
    imagejpeg($thumb, $filename, 80);
    return true;
  }

  public function tips()
  {
    $tag = '';
    if (!empty($_GET['tags'])) {
      $tag = $_GET['tags'];
      if ($tag == 'favorieten') {
        $tips = $this->tipsDAO->filterOnFavorites();
      } else {
        $tips = $this->tipsDAO->filterOnTag($tag);
      }
      if ($_SERVER['HTTP_ACCEPT'] == 'application/json') {
        echo json_encode($tips);
        exit();
      }
      $this->set('tips', $tips);
    } else {
      $this->set('tips', $this->tipsDAO->selectTips());
    }

    $this->set('tips', $this->tipsDAO->selectTips());
    $this->set('title', 'Tips');

    if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'addToFavorites') {
        if (!empty($_POST['favorite'])) {
          $favorite = 1;
        } else {
          $favorite = 0;
        }

        $data = array(
          'id' => $_POST['tip_id'],
          'favorite' => $favorite
        );

        $addToFavorites = $this->tipsDAO->addFavorite($data);

        header('Location:index.php?page=tips');
        exit();
      }
    }

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if ($contentType === "application/json") {
      $content = trim(file_get_contents("php://input"));
      $data = json_decode($content, true);

      if ($data['action'] == 'addToFavorites') {
        if (!empty($data['favorite'])) {
          $favorite = 1;
        } else {
          $favorite = 0;
        }

        $data = array(
          'id' => $data['tip_id'],
          'favorite' => $favorite
        );

        $addToFavorites = $this->tipsDAO->addFavorite($data);

        //$tip = $this->tipsDAO->selectTipsById($_GET['id']);
        $tip = array('test' => 1);
        echo json_encode($tip);
        exit();
      }
    }

    if (!empty($_GET['action'])) {
      if ($_GET['action'] == 'search') {

        $searched_results = array();

        if (!empty($_GET['title'])) {
          $searched_results = $this->tipsDAO->searchTips($_GET['title']);
        }

        if ($_SERVER['HTTP_ACCEPT'] == 'application/json') {
          echo json_encode($searched_results);
          exit();
        }

        $this->set('tips', $searched_results);
      }
    }


    if ($_GET['action'] == 'sort') {
      if (empty($_GET['sort']) || $_GET['sort'] == 'populairste') {
        $popular = $this->reactiesDAO->sortByPopular();
        $tips = [];
        foreach ($popular as $index => $popularId) {
          $tips[] = $this->tipsDAO->selectTipsById($popularId["tip_id"]);
        }
      } else {
        $tips = $this->tipsDAO->selectTips();
      }

      $this->set('tips', $tips);

      if ($_SERVER['HTTP_ACCEPT'] == 'application/json') {
        echo json_encode($tips);
        exit();
      }
    }
  }

  public function detail()
  {
    if (!empty($_GET['id'])) {
      $tip = $this->tipsDAO->selectTipsById($_GET['id']);
    }
    if (empty($tip)) {
      header('Location:index.php');
      exit();
    }

    //begin javascript
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if ($contentType === "application/json") {
      $content = trim(file_get_contents("php://input"));
      $data = json_decode($content, true);

      if ($data['action'] == 'addOpmerking') {
        $data = array(
          'tip_id' => $_GET['id'],
          'opmerking' => $data['opmerking']
        );
        if (!empty($data['opmerking'])) {
          $addOpmerking = $this->tipsDAO->insertOpmerking($data);
        }

        $opmerkingen = $this->opmerkingenDAO->selectOpmerkingenbyId($_GET['id']);
        echo json_encode($opmerkingen);
        exit();
      }


      if ($data['action'] == 'addReactie') {
        $data = array(
          'reaction_id' => $data['reaction_id'],
          'tip_id' => $_GET['id']
        );

        //werkt_niet 
        $amount = '';
        $addReactie = $this->tipsDAO->insertReaction($data);
        /*for ($i = 1; $i < 3; $i++) {
          $tip['amount' . $i] = $this->reactiesDAO->countReactiesByTip($tip['id'], $i)['amount'];
          $amount . $i = $tip['amount' . $i];
        }*/

        echo json_encode(array('result' => $addReactie, 'reaction_id' => $data['reaction_id']));
        exit();
      }

      if ($data['action'] == 'addToFavorites') {
        if (!empty($data['favorite'])) {
          $favorite = 1;
        } else {
          $favorite = 0;
        }

        $data = array(
          'id' => $_GET['id'],
          'favorite' => $favorite
        );

        $addFavorite = $this->tipsDAO->addFavorite($data);

        $tip = $this->tipsDAO->selectTipsById($_GET['id']);
        echo json_encode($tip);
        exit();
      }
    }

    if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'addToFavorites') {
        if (!empty($_POST['favorite'])) {
          $favorite = 1;
        } else {
          $favorite = 0;
        }

        $data = array(
          'id' => $_POST['tip_id'],
          'favorite' => $favorite
        );

        $addToFavorites = $this->tipsDAO->addFavorite($data);
        header('Location:index.php?page=detail&id=' . $_POST['tip_id']);
        exit();
      }
    }

    if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'addReactie') {
        $data = array(
          'reaction_id' => $_POST['reaction_id'],
          'tip_id' => $_GET['id']
        );

        $addReactie = $this->tipsDAO->insertReaction($data);
      }
    }

    for ($i = 1; $i < 3; $i++) {
      $tip['amount' . $i] = $this->reactiesDAO->countReactiesByTip($tip['id'], $i)['amount'];
    }

    if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'addOpmerking') {
        $data = array(
          'tip_id' => $_GET['id'],
          'opmerking' => $_POST['opmerking']
        );
        if (!empty($data['opmerking'])) {
          $addOpmerking = $this->tipsDAO->insertOpmerking($data);
        } else {
          $errors['opmerkingen'] = 'Voeg een opmerking toe';
          $this->set('errors', $errors['opmerkingen']);
        }
      }
    }

    if (!empty($_GET['id'])) {
      $opmerkingen = $this->opmerkingenDAO->selectOpmerkingenbyId($_GET['id']);
    }

    /* if (!empty($_POST['action'])) {
      if ($_POST['action'] == 'icoonToevoegen') {

        $data = array(
          'naam' => $_POST['naam'],
          'icoon' => $_POST['icoon']
        );

        $icoonToevoegen = $this->tipsDAO->insertIcoon($data);
      }
    } */


    $this->set('tip', $tip);
    $this->set('amount' . $i, $tip['amount' . $i]);
    $this->set('opmerkingen', $opmerkingen);
  }
}
