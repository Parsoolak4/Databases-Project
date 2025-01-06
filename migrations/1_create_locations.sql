CREATE TABLE IF NOT EXISTS
    Locations (
        locationID SERIAL PRIMARY KEY,
        name VARCHAR(50),
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
        webAddress VARCHAR(2048),
        /* Max length generally supported by modern browsers */
        postalCode CHAR(6),
        phoneNumber VARCHAR(15),
        type ENUM('Head', 'Branch'),
        capacity INT UNSIGNED,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );