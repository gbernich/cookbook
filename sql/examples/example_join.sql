SELECT	r.name AS 'recipe',
	r.hot_cold AS 'hot_cold',
	r.meal_type AS 'meal_type',
	r.prep_time AS 'prep_time',
	r.cook_time AS 'cook_time',
	r.calories AS 'calories',
	c.name AS 'compliance'
FROM Recipe r
LEFT JOIN RecipeCompliance rc on r.id = rc.recipe_id
LEFT OUTER JOIN Compliance c on c.id = rc.compliance_id;
