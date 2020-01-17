--drop database if exists Cookbook;

create database Cookbook; 

connect Cookbook; 
	
create table Recipe (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(25), 
	description VARCHAR(50),
	prep_time INT,
	cook_time INT,
	total_time INT,
	hot_cold ENUM('HOT', 'COLD'),
	compliance_whole30 BOOL,
	compliance_meatless BOOL,
	compliance_other BOOL,
	meal_type ENUM('BREAKFAST', 'LUNCH', 'DINNER', 'DESSERT'))
	ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table Ingredient (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(50)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table Measure (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(30)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 
INSERT INTO Measure (name) VALUES('cup'), ('tsp'), ('tbsp'), ('ounces');

create table Compliance (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	name VARCHAR(50)) 
	ENGINE=InnoDB DEFAULT CHARSET=utf8; 
INSERT INTO Compliance (name) VALUES('WHOLE30'), ('KETO'), ('PALEO'), ('VEGETARIAN'), ('VEGAN');

create table RecipeIngredient (recipe_id INT NOT NULL, 
	ingredient_id INT NOT NULL, 
	measure_id INT, 
	amount INT, 
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


