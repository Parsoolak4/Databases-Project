CREATE TABLE IF NOT EXISTS
    Persons (
        personID SERIAL PRIMARY KEY,
        firstName VARCHAR(50),
        lastName VARCHAR(50),
        dateOfBirth DATE,
        /* If other, then put prefered team */
        gender ENUM('m', 'f'),
        SIN CHAR(9) UNIQUE NOT NULL,
        medicareCardNumber VARCHAR(24) UNIQUE,
        telephoneNumber VARCHAR(15),
        civicNumber INT UNSIGNED,
        city VARCHAR(50),
        province ENUM(
            'NL',
            'PE',
            'NS',
            'NB',
            'QC',
            'ON',
            'MB',
            'SK',
            'AB',
            'BC',
            'YT',
            'NT',
            'NU'
        ),
        postalCode CHAR(6),
        emailAddress VARCHAR(320),
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );