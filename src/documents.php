<?php

function recupParentPath($parentID) {

  $routes = new ArrayObject();
  do {
    $pathID = $parentID;

    $parent = Folder::getFoldersById($pathID);
    $routes->append($parent);
    $parentID = $parent->parent;
  } while ($pathID != $parentID);

  return $routes;
}

function recuproutePath($routes) {
  $routePath = "";
  foreach ($routes as $route) {
    $routePath = $route->id . "/" . $routePath;
  }

  return substr("/documents/" . $routePath, 0, strlen("/documents/" . $routePath)-1);
}

function updateFoldersById($folderID, $title, $description, $habilitationArray, $gradeArray, $matricule) {
  $folder = Model::factory('Folder')->where('id', $folderID)->find_one();
  $folder->set(array(
    'title' => $title,
    'description' => $description,
    'habilitations' => implode(",", $habilitationArray),
    'grades' => implode(",", $gradeArray),
    'updateby' => $matricule
  ));
  $folder->save();
}

function updateDocumentsById($documentID, $title, $description, $habilitationArray, $gradeArray, $matricule) {
  $folder = Model::factory('Document')->where('id', $documentID)->find_one();
  $folder->set(array(
    'title' => $title,
    'description' => $description,
    'habilitations' => implode(",", $habilitationArray),
    'grades' => implode(",", $gradeArray),
    'updateby' => $matricule
  ));
  $folder->save();
}

function deleteDocument($docID, $deleteby) {
  $folder = Model::factory('Document')->where('id', $docID)->find_one();
  $folder->set(array(
    'deletedby' => $deleteby,
    'deletedat' => date("Y-m-d H:i:s"),
    'isdeleted' => true
  ));
  $folder->save();
}

function updateDocumentContent($docID, $agent, $content) {
  $folder = Model::factory('Document')->where('id', $docID)->find_one();
  $folder->set(array(
    'updateby' => $agent,
    'content' => $content
  ));
  $folder->save();
}

function setAutorisation($habName, $gradeName) {
  $habilitations = new ArrayObject();
  for ($i=0; $i < 15; $i++) {
    if (isset($_POST[$habName . $i])) {
      $habilitations->append($_POST[$habName . $i]);
    }
    else {
      $habilitations->append('0');
    }
  }
  foreach ($habilitations as $habilitation) {
    $habilitationArray[] = $habilitation;
  }

  $grades = new ArrayObject();
  for ($i=0; $i < 50; $i++) {
    if (isset($_POST[$gradeName . $i])) {
      $grades->append($_POST[$gradeName . $i]);
    }
    else {
      $grades->append('0');
    }
  }
  foreach ($grades as $grade) {
    $gradeArray[] = $grade;
  }

  $data = [
    "habilitations" => $habilitationArray,
    "grades" => $gradeArray
  ];

  return $data;
}

function defineAutorisations($type, $id, $agent) {
  if ($type == "document") {
    $element = Document::getDocument($id);
  }
  else if ($type == "folder") {
    $element = Folder::getFoldersById($id);
  }

  $habilitations = explode(",", $element->habilitations);
  $grades = explode(",", $element->grades);

  $agent->docAccess = 0;
  // On regarde d'abord par le grade
  $agent->docAccess = $grades[$agent->grade_id];

  // On regarde maintenant avec les habilitations 
  for ($i=1; $i <= 15; $i++) {
    $var = "hab_$i";
    // On vérifie que l'habilitation soit validé
    if ($agent->$var == 2) {
      $tempAccess = $habilitations[$i-1];
      // Comparaison de la valeur des accès
      if ($tempAccess > $agent->docAccess) {
        // On donne l'accès puisque meulleur
        $agent->docAccess = $tempAccess;
      }
    }
  }

  return $agent;
}

/* Function récursive de récuépration de l'arborescence */
function getTree($id, $agent) {
  $tree = new ArrayObject();
  $enfants = Folder::getSubfolderById($id);

  if ($enfants) {
    foreach ($enfants as $key => $enfant) {
      if ($enfant->id != $id) {
        $agent = defineAutorisations("folder", $enfant->id, $agent);
        if ($agent->docAccess >= 1 or $agent->admin == 1) {
          $tree[$key] = [
            'title' => $enfant->title,
            'id' => $enfant->id,
            'agentAutorisation' => $agent->docAccess,
            'subfolders' => getTree($enfant->id, $agent),
          ];
        }
      }
    }

    return $tree;
  }
  else {
    return null;
  }
}

/* On déplace un document ou répertoire */
function moveElement($type, $docID, $agent, $newParent) {

  switch ($type) {
    case 'document':
      $element = Model::factory('Document')->where('id', $docID)->find_one();
      break;
    case 'folder':
      $element = Model::factory('Folder')->where('id', $docID)->find_one();
      break;
  }

  $element->set(array(
    'parent' => $newParent,
    'updateby' => $agent
  ));
  $element->save();
}
?>
