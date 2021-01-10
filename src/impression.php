<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class generatePDF {

  private $html = "<h1>Null</h1>";
  private $nomDossier = null;

  private function encodeIMG() {
    $path = "http://" . serveurIni('Serveur', 'url') . "/assets/img/impression.png";
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
  }

  private function general() {
    // Configure Dompdf according to your needs
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);

    // Load HTML to Dompdf
    $dompdf->loadHtml($this->html);

    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser (force download)
    $dompdf->stream($this->nomDossier, [
        "Attachment" => true
    ]);
  }

  public function civil($civil, $numero) {
    // Retrieve the HTML generated in our twig file
    ob_start();
    Flight::view()->display('impression/fiche_civil.twig', array(
      'civil' => $civil,
      'routes' => Route::getListDelitByC($numero),
      'casiers' => Casier::getListCasier($numero),
      'plaintes' => Plainte::getListPlainte($numero),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Extrait Casier " . $civil->nom . " " . $civil->prenom . ".pdf";

    $this->general();
  }
}
?>
