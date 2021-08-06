/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

/* Afficher la description d'un dossier */
function showDescription(id) {
  document.getElementById(`title_${id}`).style.display = "none";
  document.getElementById(`description_${id}`).style.display = "block";
}

/* Afficher le titre d'un dossier */
function showTitle(id) {
  document.getElementById(`title_${id}`).style.display = "block";
  document.getElementById(`description_${id}`).style.display = "none";
}

function closeAll() {
  /* Reload après affichage image*/
  if (document.getElementById('viewer0')) {
    return document.location.reload();
  }

  /* Reload après affichage pdf */
  var pdf = document.getElementById('show-pdf')
  if (pdf && pdf.getAttribute('value') == 'true') {
    return document.location.reload();
  }

  var liste = [
    'addfolder', 'editfolder', 'adddocument', 'menufolder', 'pdffolder',
    'imgfolder', 'show-content', 'show-pdf', 'editdocparam', 'move-document',
    'movefolder'
  ];
  liste.forEach( item => {
    let element = document.getElementById(item)
    if (element) {
      element.style.display = "none";
    }
  });
}

/* Afficher le menu de création d'un sous dossier */
function showParamFolder(type) {
  closeAll();
  switch (type) {
    case 'menu':
      document.getElementById('menufolder').style.display = "flex";
      break;
    case 'add':
      document.getElementById('addfolder').style.display = "flex";
      break;
    case 'edit':
      document.getElementById('editfolder').style.display = "flex";
      break;
    case 'move':
      document.getElementById('movefolder').style.display = "flex";
      break;
    case 'document':
      document.getElementById('adddocument').style.display = "flex";
      break;
    case 'pdf':
      document.getElementById('pdffolder').style.display = "flex";
      break;
    case 'img':
      document.getElementById('imgfolder').style.display = "flex";
      break;
    case 'show-content':
      document.getElementById('show-content').style.display = "flex";
      break;
    case 'show-pdf':
      document.getElementById('show-pdf').style.display = "flex";
      break;
    case 'file_delete':
      deleteDocument();
      break;
    case 'file_edit':
      editDocument();
      break;
    case 'file_add_img':
      document.getElementById('imgfolder').style.display = "flex";
      break;
    case 'file_edit_propriety':
      document.getElementById('editdocparam').style.display = "flex";
      break;
    case 'file_move':
      document.getElementById('move-document').style.display = "flex";
      break;
    default:
      closeAll();
  }
}

/* Compléter le titre d'un doc si vide */
function actionPdfFile(type) {
  var myFile = document.getElementById(`upload-${type}`).files[0];

  var fileName = document.getElementById(`import_${type}_name`);

  if (fileName.value == "") {
    fileName.value = myFile.name.split('.').slice(0, myFile.name.split('.').length - 1).join('.');
  }
}

/* Afficher le contenu d'un élément */
function showImage(url) {
  showParamFolder('show-content');

  var img = document.getElementById("affiche-image");
  img.setAttribute("src", url);
  img.style.display = "none";

  const viewer = new Viewer(img, {
    inline: true,
    navbar: false,
    backdrop: false,
    button: false,
    url(img) {
      return img.src;
    },
    viewed() {
      viewer.zoomTo(1);
    },
  });
}

/* Affiche le contenue d'un pdf */
function showPdf(url) {
  var pdf = document.getElementById("content-pdf");
  pdf.setAttribute("src", url);

  showParamFolder('show-pdf');

  var contentpdf = document.getElementById("show-pdf");
  contentpdf.setAttribute("value", 'true');
}

/* Suppression document */
function deleteDocument() {
    var value = confirm("Supprimer le document ?");
    if (value) {
      alert('Le document est supprimé');
      window.location.replace(`${window.location.href}/delete`);
    }
}

/* Edition document */
function editDocument() {
  window.location.replace(`${window.location.href}/edit`);
}

/* Traduction saisie en JS */
function tradContent() {
  var converter = new showdown.Converter();
  var input = document.getElementById('content-input');
  var output = document.getElementById('content-output');

  output.innerHTML = converter.makeHtml(input.value);
}

/* Ajout d'une image et récupération de son url */
function addImgEditContent() {
  event.preventDefault(); // Arret du formulaire

  var checkNonNull = ['import_img_name', 'upload-img'];
  var continuer = true;
  checkNonNull.forEach(i => {
    if (document.getElementById(i).value == "") {
      continuer = false;
    }
  });

  if (!continuer) { return; }

  var title = document.getElementById('import_img_name').value;
  var img = document.getElementById('upload-img').files[0];
  var title = document.getElementById('import_img_name').value;
  var link = document.getElementById('link-to-copy');
  var linkDiv = document.getElementById('link-copy');

  // Taille supérieur
  if (img.size > limit.value) { return; }

  var datas = new FormData()
  datas.append('title', title);
  datas.append('image', img);

  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/documents/add/img');
  requeteAjax.send(datas);

  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    link.value = `![${title}](${resultat.filePath})`;
    linkDiv.style.display= "block";
  }
}

/* Copier le lien */
function copyLink() {
  var link = document.getElementById('link-to-copy');
  link.select();
  link.setSelectionRange(0, 99999); /* For mobile devices */
  document.execCommand("copy");
  alert("Lien copié !");
}

/* Accès pour tous */
function addAccessAll(type) {
  var element = undefined;
  for (var i = 0; i < 1000; i++) {
    element = document.getElementById(`${type}${i}`);
    if (element) {
      element.value = 1;
    }
  }
}

/* Selectionner un répertoire */
function setSelected(type, id, name) {
  var repertoire = document.getElementById(type);
  var actValue = document.getElementById('hidden-value-move');
  var actRep = document.getElementById('move-to');

  var value = repertoire.getAttribute('value');

  switch (value) {
    case 'true':
      repertoire.setAttribute('value', 'false');
      actValue.innerHTML = "";
      break;
    case 'false':
      checkPossibility(id);
      repertoire.setAttribute('value', 'true');
      actRep.innerHTML = decodeURI(name);
      actValue.innerHTML = id;
      break;
    default:
      repertoire.setAttribute('value', 'false');
      break;
  }
}

/* Unselect last select */
function unselectSelected() {
  var last = document.getElementById(`hidden-value-move`);
  if (last.innerHTML != "") {
    var repertoire = document.getElementById(`menu${last.innerHTML}_selected`);
    repertoire.setAttribute("value", 'false');
  }
}

/* Vérifie l'autorisation */
function checkPossibility(id) {
  unselectSelected();
  var newRep = document.getElementById(`menu${id}_selected`);
  var wrongRep = document.getElementById(`wrong-permissions`);
  var submitButton = document.getElementById(`submit-button`);
  if (newRep.getAttribute("permission") == 1) {
    // On peut faire des choses
    wrongRep.style.display = "none";
    submitButton.disabled = false;
  }
  else {
    // On ne peut rien faire
    wrongRep.style.display = "block";
    submitButton.disabled = true;

  }
}

/* Déplacement de l'élément */
function moveElementTo(type, id, from) {
  var to = document.getElementById(`hidden-value-move`).innerHTML;

  var value = confirm(`Déplacer le ${type} ?`);
  if (!value) { return; }

  var datas = new FormData()
  datas.append('id', id);
  datas.append('from', from);
  datas.append('to', to);

  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', `/documents/post/move/${type}/`);
  requeteAjax.send(datas);

  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    if (resultat.send) {
      switch (type) {
        case "document":
          return window.location.assign(`/documents/page/${id}`);
        case "folder":
          return window.location.assign(`/documents/redirect/folder/${id}`);
      }
    }
    else {
      closeAll();
    }
  }
}
