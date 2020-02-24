connect Cookbook; 

INSERT INTO Ingredient (name) VALUES('egg'), ('salt'), ('sugar'), ('chocolate'), ('vanilla extract'), ('flour');



INSERT INTO Recipe (name, description, prep_time, cook_time, total_time, hot_cold, compliance_whole30, compliance_meatless, compliance_other, meal_type)
			VALUES('Boiled Egg', 'A single boiled egg', 5, 15, 20, 'COLD', true, true, false, 'LUNCH');

INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount) VALUES (1, 1, NULL, 1);

INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Add egg to cold water.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Bring water to a boil.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Remove from heat.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Wait 10 minutes.');

INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (1, 1);
INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (1, 2);



INSERT INTO Recipe (name, description, prep_time, cook_time, total_time, hot_cold, compliance_whole30, compliance_meatless, compliance_other, meal_type)
			VALUES('Chocolate Cake', 'Yummy cake', 10, 30, 40, 'COLD', false, false, true, 'DESSERT');

INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 1, NULL, 3);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 2, 2, 1);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 3, 1, 2);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 4, 1, 1);

INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Preheat oven to 350.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Add eggs, flour, chocolate to pan.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Bake for 1 hour.');

INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (2, 4);



INSERT INTO Recipe (name, description, prep_time, cook_time, total_time, hot_cold, compliance_whole30, compliance_meatless, compliance_other, meal_type)
			VALUES('Roasted Brusselsprouts', 'Crispy roasted brusselsprouts', 20, 20, 40, 'HOT', true, true, false, 'DINNER');

INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (3, 4, 2, 1);

INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Preheat oven to 400..');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Peel outer leaves from sprouts.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Cut the stems off.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Cut the sprouts in half.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Toss in olive oil, salt, and pepper.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (3, 'Roast for 20 minutes, or until brown.');

INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (3, 1);
INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (3, 3);
INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (3, 4);



SELECT r.name AS 'Recipe', 
	ri.amount_whole AS 'Amount Whole', 
	ri.amount_numerator AS 'Amount Num', 
	ri.amount_denominator AS 'Amount Den', 
	mu.name AS 'Unit of Measure', 
	i.name AS 'Ingredient' 
FROM Recipe r 
JOIN RecipeIngredient ri on r.id = ri.recipe_id 
JOIN Ingredient i on i.id = ri.ingredient_id 
LEFT OUTER JOIN Measure mu on mu.id = measure_id;

SELECT r.name AS 'Recipe', 
	ri.instruction AS 'RecipeInstruction' 
FROM Recipe r 
JOIN RecipeInstruction ri on r.id = ri.recipe_id;

SELECT r.name AS 'Recipe', 
	c.name AS 'Compliance' 
FROM Recipe r 
JOIN RecipeCompliance rc on r.id = rc.recipe_id
LEFT OUTER JOIN Compliance c on c.id = compliance_id;
