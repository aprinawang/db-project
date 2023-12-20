DROP TABLE IF EXISTS User;

CREATE TABLE User (
    user_id INTEGER NOT NULL,
    username VARCHAR(25) NOT NULL,
    email VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id)
);

DROP TABLE IF EXISTS Recipe;

CREATE TABLE Recipe (
    recipe_name VARCHAR(255) NOT NULL,
    recipe_id INTEGER NOT NULL,
    minutes INTEGER,
    user_id INTEGER NOT NULL,
    date DATETIME,
    description VARCHAR(6000),
    calories INTEGER,
    PRIMARY KEY (recipe_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

DROP TABLE IF EXISTS Ingredient;

CREATE TABLE Ingredient (
    ingredient_id INTEGER NOT NULL,
    Energy_Kcal INTEGER,
    Protein FLOAT,
    Carbohydrate FLOAT,
    Fiber FLOAT,
    Sugar FLOAT,
    Calcium FLOAT,
    Iron FLOAT,
    Magnesium FLOAT,
    Sodium FLOAT,
    Cholestrl FLOAT,
    description VARCHAR(25) NOT NULL,
    unit INTEGER,
    volume_quantity INTEGER,
    volume_desc VARCHAR(25),
    weight_quantity INTEGER,
    weight_desc VARCHAR(25),
    PRIMARY KEY (ingredient_id)
);

DROP TABLE IF EXISTS RecipeIngredientLink;

CREATE TABLE RecipeIngredientLink (
    recipe_id INTEGER NOT NULL,
    ingredient_id INTEGER NOT NULL,
    quantity DECIMAL(4,2),
    quantity_desc VARCHAR(25),
    variant VARCHAR(25) NOT NULL,
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id)
);

DROP TABLE IF EXISTS Interaction;

CREATE TABLE Interaction (
    user_id INTEGER NOT NULL,
    recipe_id INTEGER NOT NULL,
    date DATETIME,
    rating INTEGER,
    review VARCHAR(20000),
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id)
);

DROP TABLE IF EXISTS Allergy;

CREATE TABLE Allergy (
    allergy VARCHAR(25) NOT NULL,
    allergy_id INTEGER NOT NULL,
    PRIMARY KEY (allergy_id)
);

DROP TABLE IF EXISTS Allergens;

CREATE TABLE Allergens (
    allergy_id INTEGER NOT NULL,
    ingredient_id INTEGER NOT NULL,
    FOREIGN KEY (allergy_id) REFERENCES Allergy(allergy_id),
    FOREIGN KEY (ingredient_id) REFERENCES Ingredient(ingredient_id)
);

DROP TABLE IF EXISTS Tag;

CREATE TABLE Tag (
    tag_id INTEGER NOT NULL,
    tag_name VARCHAR(255),
    PRIMARY KEY (tag_id)
);

DROP TABLE IF EXISTS TagLink;

CREATE TABLE TagLink (
    recipe_id INTEGER NOT NULL,
    tag_id INTEGER,
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id),
    FOREIGN KEY (tag_id) REFERENCES Tag(tag_id)
);

DROP TABLE IF EXISTS Steps;

CREATE TABLE Steps (
    recipe_id INTEGER NOT NULL,
    steps VARCHAR(255),
    step_order INTEGER,
    FOREIGN KEY (recipe_id) REFERENCES Recipe(recipe_id)
);

DROP TABLE IF EXISTS Conversions;

CREATE TABLE Conversions (
    from_unit VARCHAR(25),
    to_unit VARCHAR(25),
    factor DECIMAL(5,2)
);
