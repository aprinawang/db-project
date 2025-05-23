# db-project

# data info

`allergens (allergy_id, ingredient_id)`:
- indicates which ingredient (by id) is an allergen for some allergy (by id)


`allergies (allergy, allergy_id)`:
- allergy ids and their names (only working with lactose and nut allergies)


`conversions (from, to, factor)`:
- offers a way to convert between different measurement quantities
- from is the starting measurement
- to is the result
- factor is the number you have to multiply by to go from start to result 
  - this will be helpful for summing nutrition facts for each recipe 
- some measurement quantites are weight measurements and some are volume
  - there are only conversions available between two weights or two volumes
- there should be no primary key here i believe?

`ingredient_in (recipe_id, ingredient_id, quantity, quantity_desc, variant)`:
- ingredients (by id) and the recipe that they are used in
- variant is the name that the recipe uses to refer to the ingredient (which may
 be different from the name in the ingredient table)
- quantity is a (float) number that represents how much of the ingredient is used
  - some of these may be null (NaN/None)!
  - if quantity the number is null, that means there isn't a measurement for the
    ingredient (e.g. a dash of salt would get NaN quantity)
- quantity_desc is a string describing the unit of quantity (same strings as found in conversions)
  - some of these may be null as well!
  - it should be the case that if quantity_desc is not null, quantity is also 
    not null but there could be anomolies :(
  - and if quantity IS null, then quantity_desc will also always be null
  - quantity_desc being null but quantity being not null means there are some
    [quantity] number of units of the ingredient (e.g. 3 whole bananas would be
    quantity = 3; quantity_desc = null)

`ingredients (NDB_No, Energ_Kcal, ..., Cleaned_Desc, unit, volume_quantity, volume_desc, weight_quantity, weight_desc)`:
- lists ingredients and various information
- NDB_No is the id
- Cleaned_Desc is the name of the ingredient
- I selected the nutrients that I thought were the most common/useful
- unit, volume_quantity, volume_desc, weight_quantity, and weight_desc are all
  used to represent the quantity for which the nutrient info is for
  - some of these fields may be null!!!
  - unit represents the number of units of the ingredient (i.e. 1 banana means unit = 1, 2 eggs means unit = 2)
    - 1 banana then contains 350 calories for example, if that's the associated
    value in the 'Energ_Kcal' column
  - (volume_quantity, volume_desc) and (weight_quantity, weight_desc) work the 
  same as (quantity, quantity_desc) above in terms of meaning
    - e.g. 1 tbsp of butter means volume_quantity = 1 and volume_desc = tablespoons
    - it should be the case that if the quantity is not null, the desc is also
    not null and if the quantity is null, then desc is null as well - however,
    again, there may be anomolies

`interactions(user_id, recipe_id, date, rating, review)`:
- reviews made by users (by id) on certain recipes (by id)
- rating is out of 5
- review a string representing the comment left with the rating

`recipes(name, id, contributor_id, submitted, tags, description, calories)`:
- recipe information
- contributor_id is the user that submitted the recipe
- submitted is the date that it was added
- tags is left as is
  - this csv should be run through the separating_tags notebook to extract those
  - then the tags column should be dropped so it doesn't remain in the final data
- description - idk if we want to keep this, but I left it in so up to you
- calories - number of calories in one serving

`steps (id, steps, order)`
- ended up adding this in because it was pretty simple and adds complexity
- step of a recipe
- id is the recipe id that the step belongs to
  - if we want to include a primary key, we should be able to just use the index
- order is the number step that it is of the recipe (e.g. order = 3 means that
step is the third of the recipe)

`users (id, name, email)`
- generated user data