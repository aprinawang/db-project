<!DOCTYPE html>
<html>
<head>
    <title>Recipe Search Results</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .recipe { margin-bottom: 30px; padding: 10px; border: 1px solid #ddd; background: #f0f8ff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recipe Search Results</h1>

        <?php
        // Replace these variables with your actual database details
      ini_set('error_reporting', E_ALL);
      ini_set('display_errors', true);

      include('db_connect.php');

        if (isset($_POST['searchRecipes'])) {
          $ingredients = $_POST['ingredients'];
            
            // The stored procedure call
            $query = "CALL SearchRecipesByIngredients('$ingredients')";

            // Execute the query and handle the results
            if ($result = mysqli_query($db, $query)) {
              echo "<h1>Listing Recipes with the ingredients: $ingredients</h1>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='recipe'>";
                    echo "<h3>" . htmlspecialchars($row['recipe_name']) . "</h3>";
                    echo "<p><b>Time to cook:</b> " . htmlspecialchars($row['minutes']) . " minutes</p>";
                    echo "<p><b>Description:</b> " . htmlspecialchars($row['description']) . "</p>";
                    echo "<p><b>Calories:</b> " . htmlspecialchars($row['calories']) . "</p>";


                    // Button to view full details
                echo "<form method='post' action='recipe_details.php'>";
                echo "<input type='hidden' name='recipeId' value='" . htmlspecialchars($row['recipe_id']) . "'>";
                echo "<button type='submit' name='viewDetails'>View Details</button>";
                echo "</form>";

                echo "</div>";
                }
                mysqli_free_result($result);
            } else {
                echo "Error: " . mysqli_error($db);
            }

        }
        if (isset($_POST['filterByTimeRange'])) {
          $minMinutes = $_POST['minMinutes'];
          $maxMinutes = $_POST['maxMinutes'];
      
          // The stored procedure call
          $query = "CALL GetRecipesByTimeRange($minMinutes,$maxMinutes)";
      
          // Execute the query and handle the results
          if ($result = mysqli_query($db, $query)) {
              echo "<h4>Recipes:</h4>";
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<div class='recipe'>";
                  echo "<h3>" . htmlspecialchars($row['recipe_name']) . "</h3>";
                  echo "<p><b>Time to cook:</b> " . htmlspecialchars($row['minutes']) . " minutes</p>";
                  echo "<p><b>Description:</b> " . htmlspecialchars($row['description']) . "</p>";
                  echo "<p><b>Calories:</b> " . htmlspecialchars($row['calories']) . "</p>";

                  // Button to view full details
                echo "<form method='post' action='recipe_details.php'>";
                echo "<input type='hidden' name='recipeId' value='" . htmlspecialchars($row['recipe_id']) . "'>";
                echo "<button type='submit' name='viewDetails'>View Details</button>";
                echo "</form>";

                echo "</div>";
              }
              mysqli_free_result($result);
          } else {
              echo "Error: " . mysqli_error($db);
          }
      }

      if (isset($_POST['search'])) {
        // Get the search input
        $searchInput = $_POST['searchInput'];
    
        // The stored procedure call
        $query = "CALL SearchRecipesByName('$searchInput')";
    
        // Execute the query and handle the results
        if ($result = mysqli_query($db, $query)) {
            echo "<h2>Search Results:</h2>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='recipe'>";
                echo "<h3>" . htmlspecialchars($row['recipe_name']) . "</h3>";
                echo "<p><b>Time to cook:</b> " . htmlspecialchars($row['minutes']) . " minutes</p>";
                echo "<p><b>Description:</b> " . htmlspecialchars($row['description']) . "</p>";
                echo "<p><b>Calories:</b> " . htmlspecialchars($row['calories']) . "</p>";

                // Button to view full details
                echo "<form method='post' action='recipe_details.php'>";
                echo "<input type='hidden' name='recipeId' value='" . htmlspecialchars($row['recipe_id']) . "'>";
                echo "<button type='submit' name='viewDetails'>View Details</button>";
                echo "</form>";

                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "Error: " . mysqli_error($db);
        }
    
      }
        mysqli_close($db);
        ?>

        <a href="index.html">Back to Home</a>
    </div>
</body>
</html>
