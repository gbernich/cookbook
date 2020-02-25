create database Cookbook;

connect Cookbook;

create table Recipe (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(80) NOT NULL,
	description VARCHAR(250) NOT NULL,
	servings INT NOT NULL,
	prep_time INT NOT NULL,
	cook_time INT NOT NULL,
	total_time INT NOT NULL,
	hot_cold ENUM('HOT', 'COLD') NOT NULL,
	meal_type ENUM('BREAKFAST', 'LUNCH', 'DINNER', 'DESSERT') NOT NULL,
	calories INT,
	total_fat INT,
	saturated_fat INT,
	cholesterol INT,
	sodium INT,
	carbohydrates INT,
	fiber INT,
	sugar INT,
	protein INT)
	ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table Ingredient (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(100) NOT NULL) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table Measure (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(30)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table Compliance (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(50)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table RecipeIngredient (recipe_id INT NOT NULL, 
	ingredient_id INT NOT NULL, 
	measure_id INT, 
	amount_whole INT, 
	amount_numerator INT, 
	amount_denominator INT, 
	CONSTRAINT fk_recipe FOREIGN KEY(recipe_id) REFERENCES Recipe(id), 
	CONSTRAINT fk_ingredient FOREIGN KEY(ingredient_id) REFERENCES Ingredient(id), 
	CONSTRAINT fk_measure FOREIGN KEY(measure_id) REFERENCES Measure(id)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table RecipeInstruction (recipe_id INT NOT NULL, 
	instruction VARCHAR(200),
	CONSTRAINT fk_recipe2 FOREIGN KEY(recipe_id) REFERENCES Recipe(id))
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table RecipeCompliance (recipe_id INT NOT NULL, 
	compliance_id INT NOT NULL,
	CONSTRAINT fk_recipe3 FOREIGN KEY(recipe_id) REFERENCES Recipe(id))
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table RecipeLog (recipe_id INT NOT NULL, 
	date DATE NOT NULL,
	notes VARCHAR(200),
	CONSTRAINT fk_recipe4 FOREIGN KEY(recipe_id) REFERENCES Recipe(id))
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

