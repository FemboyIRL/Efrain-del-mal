<?php
session_start();
$connection = pg_connect("host=localhost dbname=biblioteca_escolar user=postgres password=1234");
if (!$connection) {
    echo "Ha ocurrido un error";
    exit;
}

if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
    $search_query = pg_escape_string($_POST['search_query']);
    
    header("Location: screen.php?searchBar=" . urlencode($search_query));
    exit;
} else {
    header("Location: screen.php?errorSearchBarVacia=1"); 
    exit;
}
?>
