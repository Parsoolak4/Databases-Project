CREATE TABLE IF NOT EXISTS
    Personnel (
        locationID BIGINT UNSIGNED NOT NULL,
        personID BIGINT UNSIGNED NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE,
        `role` ENUM('Administrator', 'Trainer', 'Other'),
        mandate ENUM('Volunteer', 'Salary'),
        isGeneralManager BOOLEAN,
        PRIMARY KEY (locationID, personID, startDate),
        FOREIGN KEY (locationID) REFERENCES Locations (locationID),
        FOREIGN KEY (personID) REFERENCES Persons (personID),
        CHECK (
            endDate IS NULL
            OR startDate < endDate
        )
    );

----------------------------------------------------------------------------------------------------------------------------------
CREATE VIEW IF NOT EXISTS
    Active_Personnel AS
SELECT
    *
FROM
    Personnel
WHERE
    endDate IS NULL;

DELIMITER $$

----------------------------------------------------------------------------------------------------------------------------------
CREATE TRIGGER validate_personnel_start_date AFTER
INSERT
    ON Personnel FOR EACH ROW
BEGIN IF EXISTS (
    SELECT
        1
    FROM
        Personnel
    WHERE
        personID = NEW.personID
        AND (
            (NEW.startDate BETWEEN startDate AND endDate)
            OR (
                NEW.endDate > startDate
                AND NEW.endDate < endDate
            )
        )
) THEN
SIGNAL SQLSTATE '45000'
SET
    MESSAGE_TEXT = 'Start date must be later than most recent endDate';

END IF;

END;