CREATE TABLE IF NOT EXISTS
    Teams (
        teamID SERIAL PRIMARY KEY,
        locationID BIGINT UNSIGNED NOT NULL,
        `name` VARCHAR(50),
        gender ENUM('m', 'f'),
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (locationID) REFERENCES Locations (locationID),
    );
----------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS
    Team_Members (
        teamID BIGINT UNSIGNED NOT NULL,
        personID BIGINT UNSIGNED,
        startDate DATE NOT NULL,
        endDate DATE,
        `role` ENUM(
            'coach',
            'goalkeeper',
            'defender',
            'midfielder',
            'forward',
            'other'
        ),
        isCaptain BOOLEAN,
        PRIMARY KEY (teamID, personID, startDate),
        FOREIGN KEY (teamID) REFERENCES Teams (teamID),
        FOREIGN KEY (personID) REFERENCES Persons (personID),
        CHECK (
            endDate IS NULL
            OR startDate < endDate
        )
    );

----------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS
    Scheduled_Events (
        -- if both teams are visiting, then choose one arbitrarily to be home
        homeTeamID BIGINT UNSIGNED NOT NULL,
        visitingTeamID BIGINT UNSIGNED NOT NULL,
        `date` TIMESTAMP NOT NULL,
        /* Events may be international, in which case we can't rely on the canadian address system.
         * Additionally, it may be desirable to include a more descriptive location than what can be
         * done with civicNumber + postalCode.
         */
        `address` TEXT,
        `type` ENUM('training', 'game'),
        homeScore INT UNSIGNED DEFAULT 0,
        visitingScore INT UNSIGNED DEFAULT 0,
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (homeTeamID, visitingTeamID, `date`),
        FOREIGN KEY (homeTeamID) REFERENCES Teams (teamID),
        FOREIGN KEY (visitingTeamID) REFERENCES Teams (teamID)
    );

----------------------------------------------------------------------------------------------------------------------------------

CREATE VIEW IF NOT EXISTS
    Active_Team_Members AS
SELECT
    *
FROM
    Team_Members
WHERE
    endDate IS NULL;

----------------------------------------------------------------------------------------------------------------------------------
CREATE TRIGGER validate_team_member_start_date AFTER
INSERT
    ON Team_Members FOR EACH ROW
BEGIN IF EXISTS (
    SELECT
        1
    FROM
        Team_Members
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