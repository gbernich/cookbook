connect Cookbook; 

INSERT INTO Ingredient (name) VALUES('egg'), ('salt'), ('sugar'), ('chocolate'), ('vanilla extract'), ('flour');



INSERT INTO Recipe (name, description, prep_time, cook_time, total_time) VALUES('Boiled Egg', 'A single boiled egg', 5, 15, 20);

INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount) VALUES (1, 1, NULL, 1);

INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Add egg to cold water.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Bring water to a boil.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Remove from heat.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (1, 'Wait 10 minutes.');

INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (1, 1);
INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (1, 2);


INSERT INTO Recipe (name, description, prep_time, cook_time, total_time) VALUES('Chocolate Cake', 'Yummy cake', 10, 30, 40);

INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 1, NULL, 3);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 2, 2, 1);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 3, 1, 2);
INSERT INTO RecipeIngredient (recipe_id, ingredient_id, measure_id, amount)  VALUES (2, 4, 1, 1);

INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Preheat oven to 350.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Add eggs, flour, chocolate to pan.');
INSERT INTO RecipeInstruction (recipe_id, instruction) VALUES (2, 'Bake for 1 hour.');

INSERT INTO RecipeCompliance (recipe_id, compliance_id) VALUES (2, 4);



SELECT r.name AS 'Recipe', 
	ri.amount AS 'Amount', 
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
