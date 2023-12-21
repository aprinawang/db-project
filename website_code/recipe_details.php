<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Details</title>
    <style>
        .recipe-nutrition {
    background: #f0f0f0;
    padding: 15px;
    margin-top: 20px;
    border-radius: 5px;
}

.nutrition-table {
    width: 100%;
    border-collapse: collapse;
}

.nutrition-table td {
    border-bottom: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.nutrition-table tr:last-child td {
    border-bottom: none;
}
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f8ff;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333366;
        }
        .recipe-details, .recipe-ingredients, .recipe-steps {
            background: #f0f8ff;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
        }
        ul, ol {
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php
// Include the database connection file
include('db_connect.php');

// Check if the button to view details is clicked
if (isset($_POST['viewDetails'])) {
    // Get the selected recipe id
    $recipeId = $_POST['recipeId'];

    // The stored procedure call to get full recipe details
    $detailsQuery = "CALL GetFullRecipeDetails($recipeId)";

    // Execute the query and handle the results
    if ($detailsResult = mysqli_multi_query($db, $detailsQuery)) {
        do {
            // Store the result set from the current query
            if ($result = mysqli_store_result($db)) {
                // Display the details from the current result set
                while ($row = mysqli_fetch_assoc($result)) {
                    // Determine the type of information based on available columns
                    if (isset($row['recipe_name'])) {
                        // RecipeInformation
                        // Display the details from RecipeInformation
                        //while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='recipe-details'>";
                            echo "<h2>Recipe Details</h2>";
                            echo "<p><b>Recipe ID:</b> " . htmlspecialchars($row['recipe_id']) . "</p>";
                            echo "<p><b>Recipe Name:</b> " . htmlspecialchars($row['recipe_name']) . "</p>";
                            echo "<p><b>Minutes:</b> " . htmlspecialchars($row['minutes']) . " minutes</p>";
                            echo "<p><b>User ID:</b> " . htmlspecialchars($row['user_id']) . "</p>";
                            echo "<p><b>Date:</b> " . htmlspecialchars($row['date']) . "</p>";
                            echo "<p><b>Description:</b> " . htmlspecialchars($row['description']) . "</p>";
                            echo "<p><b>Calories:</b> " . htmlspecialchars($row['calories']) . "</p>";
                            echo "</div>";
                        //}
                    } elseif (isset($row['ingredient'])) {
                        // RecipeIngredients
                        // Display the list of ingredients
                        echo "<div class='recipe-ingredients'>";
                        echo "<h2>Ingredients</h2>";
                        echo "<ul>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li>" . htmlspecialchars($row['ingredient']) . " - " . htmlspecialchars($row['quantity']) . " " . htmlspecialchars($row['quantity_desc']) . (isset($row['variant']) ? " (" . htmlspecialchars($row['variant']) . ")" : "") . "</li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                    } elseif (isset($row['steps'])) {
                        // RecipeSteps
                        // Display the list of steps
                        echo "<div class='recipe-steps'>";
                        echo "<h2>Recipe Steps</h2>";
                        echo "<ol>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li>" . htmlspecialchars($row['steps']) . "</li>";
                        }
                        echo "</ol>";
                        echo "</div>";
                    } elseif (isset($row['Protein_pct'])) {
                        // RecipeNutrition
                        // Display the nutrition details
                        echo "<div class='recipe-nutrition'>";
                        echo "<h2>Nutrition Information Daily Value Information (Calculated) </h2>";
                        echo "<table class='nutrition-table'>";
                        echo "<tr><td>Protein</td><td>" . intval($row['Protein_pct']) . "%</td></tr>";
                        echo "<tr><td>Carbohydrate</td><td>" . intval($row['Carbohydrate_pct']) . "%</td></tr>";
                        echo "<tr><td>Fiber</td><td>" . intval($row['Fiber_pct']) . "%</td></tr>";
                        echo "<tr><td>Sugar</td><td>" . intval($row['Sugar_pct']) . "%</td></tr>";
                        echo "<tr><td>Calcium</td><td>" . intval($row['Calcium_pct']) . "%</td></tr>";
                        echo "<tr><td>Iron</td><td>" . intval($row['Iron_pct']) . "%</td></tr>";
                        echo "<tr><td>Magnesium</td><td>" . intval($row['Magnesium_pct']) . "%</td></tr>";
                        echo "<tr><td>Sodium</td><td>" . intval($row['Sodium_pct']) . "%</td></tr>";
                        echo "<tr><td>Cholesterol</td><td>" . intval($row['Cholesterol_pct']) . "%</td></tr>";
                        echo "</table>";
                        echo "</div>";
                    }
                }
                mysqli_free_result($result);
            }

            // Move to the next result set
            if (mysqli_more_results($db)) {
                mysqli_next_result($db);
            }
        } while (mysqli_more_results($db));
    } else {
        echo "Error: " . mysqli_error($db);
    }
}

// Close the database connection
mysqli_close($db);
?>
<a href="index.html">Back to Home</a>
    </div>
</body>
</html>
