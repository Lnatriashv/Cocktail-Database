CREATE TABLE Person (
    PersonID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Age INT NOT NULL
);

CREATE TABLE Critics (
    CriticsID INT PRIMARY KEY,
    FOREIGN KEY (CriticsID) REFERENCES Person(PersonID)
);

CREATE TABLE User (
    UserID INT PRIMARY KEY,
    FOREIGN KEY (UserID) REFERENCES Person(PersonID)
);

CREATE TABLE Cocktail (
    CocktailID INT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Recipe TEXT NOT NULL
);

CREATE TABLE Alcoholic (
    CocktailID INT PRIMARY KEY,
    AlcoholPercentage DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (CocktailID) REFERENCES Cocktail(CocktailID)
);

CREATE TABLE NonAlcoholic (
    CocktailID INT PRIMARY KEY,
    FOREIGN KEY (CocktailID) REFERENCES Cocktail(CocktailID)
);

CREATE TABLE Ingredient (
    IngredientID INT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Amount VARCHAR(50) NOT NULL
);

CREATE TABLE FizzyDrink (
    IngredientID INT PRIMARY KEY,
    Flavour VARCHAR(50) NOT NULL,
    FOREIGN KEY (IngredientID) REFERENCES Ingredient(IngredientID)
);

CREATE TABLE FruitJuice (
    IngredientID INT PRIMARY KEY,
    Fruit VARCHAR(50) NOT NULL,
    FOREIGN KEY (IngredientID) REFERENCES Ingredient(IngredientID)
);

CREATE TABLE Cocktail_ingredients (
    CocktailID INT,
    IngredientID INT,
    Amount VARCHAR(50) NOT NULL,
    PRIMARY KEY (CocktailID, IngredientID),
    FOREIGN KEY (CocktailID) REFERENCES Cocktail(CocktailID),
    FOREIGN KEY (IngredientID) REFERENCES Ingredient(IngredientID)
);

CREATE TABLE Rating (
    UserID INT,
    CocktailID INT,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    RatingDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID, CocktailID),
    FOREIGN KEY (UserID) REFERENCES User(UserID),
    FOREIGN KEY (CocktailID) REFERENCES Cocktail(CocktailID)
);

CREATE TABLE Bartender (
  BartenderID INT PRIMARY KEY AUTO_INCREMENT,
  YearsOfExperience INT,
  Name VARCHAR(255)
  );