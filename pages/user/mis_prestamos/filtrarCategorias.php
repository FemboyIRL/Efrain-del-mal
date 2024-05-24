<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['categories'])) {
        $selected_categories = $_POST['categories'];
        foreach ($selected_categories as $category) {
            header("Location: screen.php?categories=$category");
            echo "Categoría seleccionada: " . htmlspecialchars($category) . "<br>";
        }
    } else {
        echo "No se seleccionaron categorías.";
    }
}
?>