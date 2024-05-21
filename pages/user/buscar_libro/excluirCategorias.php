<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['excludeCategories'])) {
        $excluded_categories = $_POST['excludeCategories'];
        foreach ($excluded_categories as $excluded_category) {
            echo "Categoría seleccionada: " . htmlspecialchars($excluded_category) . "<br>";
        }
        header("Location: screen.php?excludedCategories=" . urlencode(implode(",", $excluded_categories)));
    } else {
        echo "No se seleccionaron categorías.";
    }
}
?>