-- STORED PROCEDURE FOR SEARCHING FOR INGREDIENTS

-- Drop the stored procedure
DROP PROCEDURE IF EXISTS SearchRecipesByIngredients;

DELIMITER //

CREATE PROCEDURE SearchRecipesByIngredients(IN ingredients_list VARCHAR(255))
BEGIN
    -- Query to search for recipes that include ingredients similar to those listed
    SELECT
        R.recipe_id,
        R.recipe_name,
        R.minutes,
        R.description,
        R.calories
    FROM Recipe R
    JOIN RecipeIngredientLink RIL ON R.recipe_id = RIL.recipe_id
    JOIN Ingredient I ON RIL.ingredient_id = I.ingredient_id
    JOIN 
    (
        SELECT 1 n UNION ALL SELECT 2
        UNION ALL SELECT 3 UNION ALL SELECT 4
        UNION ALL SELECT 5 UNION ALL SELECT 6
        -- Add more numbers here if you expect more ingredients
    ) numbers ON CHAR_LENGTH(ingredients_list) 
               - CHAR_LENGTH(REPLACE(ingredients_list, ',', '')) 
               >= numbers.n - 1
    WHERE I.Cleaned_Desc LIKE CONCAT('%', SUBSTRING_INDEX(SUBSTRING_INDEX(ingredients_list, ',', numbers.n), ',', -1), '%')
    AND numbers.n <= (LENGTH(ingredients_list) - LENGTH(REPLACE(ingredients_list, ',', '')) + 1)
    GROUP BY R.recipe_id
    HAVING COUNT(DISTINCT I.Cleaned_Desc) = LENGTH(ingredients_list) - LENGTH(REPLACE(ingredients_list, ',', '')) + 1;
END //

DELIMITER ;

CALL SearchRecipesByIngredients('orange,milk');

-- STORED PROCEDURE that filters recipes by minutes

DELIMITER //

CREATE PROCEDURE GetRecipesByTimeRange(IN minMinutes INT, IN maxMinutes INT)
BEGIN
    SELECT
        recipe_id,
        recipe_name,
        minutes,
        description,
        calories
    FROM Recipe
    WHERE minutes BETWEEN minMinutes AND maxMinutes
    LIMIT 20;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SearchRecipesByName(IN searchSubstring VARCHAR(255))
BEGIN
    SELECT
        DISTINCT R.recipe_id,
        R.recipe_name,
        R.minutes,
        R.description,
        R.calories
    FROM Recipe R
    LEFT JOIN TagLink TL ON R.recipe_id = TL.recipe_id
    LEFT JOIN Tag T ON TL.tag_id = T.tag_id
    WHERE R.recipe_name LIKE CONCAT('%', searchSubstring, '%')
    LIMIT 20;
END //

DELIMITER ;

CREATE VIEW RecipeIngredients AS
SELECT
    ril.recipe_id,
    i.Cleaned_Desc AS ingredient,
    ril.quantity,
    ril.quantity_desc,
    ril.variant
FROM
    RecipeIngredientLink ril
JOIN
    Ingredient i ON ril.ingredient_id = i.ingredient_id;

CREATE VIEW RecipeSteps AS
SELECT
    recipe_id,
    step_order,
    steps
FROM
    Steps
ORDER BY
    recipe_id, step_order;

CREATE VIEW RecipeInformation AS
SELECT
    recipe_id,
    recipe_name,
    minutes,
    user_id,
    date,
    description,
    calories
FROM
    Recipe;

DROP PROCEDURE IF EXISTS GetFullRecipeDetails;
DELIMITER //
CREATE PROCEDURE GetFullRecipeDetails(IN recipe_id_param INTEGER)
BEGIN
    SELECT * FROM RecipeInformation WHERE recipe_id = recipe_id_param;
    SELECT * FROM RecipeIngredients WHERE recipe_id = recipe_id_param;
    SELECT * FROM RecipeSteps WHERE recipe_id = recipe_id_param;
    SELECT * FROM RecipeNutrition WHERE recipe_id = recipe_id_param;
END //
DELIMITER ;

CALL RecipeNutrition(8559);
SELECT * FROM RecipeNutrition WHERE recipe_id = 8559;

DROP VIEW IF EXISTS RecipeNutrition;
CREATE VIEW RecipeNutrition AS
SELECT
    ril.recipe_id,
    (SUM(i.Protein * ril.quantity) / 50) * 100 AS Protein_pct,
    (SUM(i.Carbohydrate * ril.quantity) / 300) * 100 AS Carbohydrate_pct,
    (SUM(i.Fiber * ril.quantity) / 25) * 100 AS Fiber_pct,
    (SUM(i.Sugar * ril.quantity) / 50) * 100 AS Sugar_pct,
    (SUM(i.Calcium * ril.quantity) / 1300) * 100 AS Calcium_pct,
    (SUM(i.Iron * ril.quantity) / 18) * 100 AS Iron_pct,
    (SUM(i.Magnesium * ril.quantity) / 400) * 100 AS Magnesium_pct,
    (SUM(i.Sodium * ril.quantity) / 2300) * 100 AS Sodium_pct,
    (SUM(i.Cholestrl * ril.quantity) / 300) * 100 AS Cholesterol_pct
FROM
    RecipeIngredientLink ril
INNER JOIN
    Ingredient i ON ril.ingredient_id = i.ingredient_id
GROUP BY
    ril.recipe_id;
