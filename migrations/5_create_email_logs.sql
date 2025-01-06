CREATE TABLE IF NOT EXISTS
    Email_Log (
        createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        toPersonID BIGINT UNSIGNED NOT NULL,
        locationID BIGINT UNSIGNED NOT NULL,
        -- A person's email is mutable, therefore we store the receiver email in addition to receiver id
        toPersonEmail VARCHAR(320) NOT NULL,
        `subject` VARCHAR(78) NOT NULL,
        body VARCHAR(100) NOT NULL,
        PRIMARY KEY (createdAt, toPersonID, locationID),
        FOREIGN KEY (toPersonID) REFERENCES Persons (personID),
        FOREIGN KEY (locationID) REFERENCES Locations (locationID)
    );