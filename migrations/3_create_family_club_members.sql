CREATE TABLE IF NOT EXISTS
    ClubMember_Relatives (
        clubMemberID BIGINT UNSIGNED NOT NULL,
        relativeID BIGINT UNSIGNED NOT NULL,
        relationship ENUM(
            'Father',
            'Mother',
            'GrandFather',
            'GrandMother',
            'Tutor',
            'Partner',
            'Friend',
            'Other'
        ),
        createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        /* Can change if a friend becomes a parent's partner */
        updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (clubMemberID, relativeID),
        FOREIGN KEY (clubMemberID) REFERENCES Persons (personID),
        FOREIGN KEY (relativeID) REFERENCES Persons (personID)
    );

----------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS
    ClubMember_Guardians (
        clubMemberID BIGINT UNSIGNED NOT NULL,
        guardianID BIGINT UNSIGNED NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE,
        `priority` INT UNSIGNED NOT NULL,
        PRIMARY KEY (clubMemberID, guardianID, startDate),
        FOREIGN KEY (clubMemberID) REFERENCES Persons (personID),
        FOREIGN KEY (guardianID) REFERENCES Persons (personID),
        CHECK (
            endDate IS NULL
            OR startDate < endDate
        )
    );

----------------------------------------------------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS
    Family_ClubMember_Location (
        locationID BIGINT UNSIGNED NOT NULL,
        personID BIGINT UNSIGNED NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE,
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
    Active_Family_ClubMember_Location AS
SELECT
    *
FROM
    Family_ClubMember_Location
WHERE
    endDate IS NULL;

CREATE VIEW IF NOT EXISTS
    Active_ClubMember_Guardians AS
SELECT
    *
FROM
    ClubMember_Guardians
WHERE
    endDate IS NULL;

----------------------------------------------------------------------------------------------------------------------------------
CREATE VIEW IF NOT EXISTS
    Active_ClubMembers AS
SELECT
    p.*,
    locationID,
    fcl.startDate as locationStartDate,
    FIRST_VALUE(guardianID) OVER (
        PARTITION BY
            personID
        ORDER BY
            `priority` ASC
    ) as primaryGuardianID,
    NTH_VALUE(guardianID, 2) OVER (
        PARTITION BY
            personID
        ORDER BY
            `priority` ASC
    ) as secondaryGuardianID
FROM
    Persons p
    JOIN Active_ClubMember_Guardians cg ON (cg.clubMemberID = p.personID)
    JOIN Active_Family_ClubMember_Location fcl ON (p.personID = fcl.personID)
WHERE
    (
        TIMESTAMPDIFF (YEAR, p.dateOfBirth, CURRENT_DATE) BETWEEN 4 AND 10
    )
GROUP BY
    p.personID;

----------------------------------------------------------------------------------------------------------------------------------
CREATE TRIGGER validate_clubmember_start_date AFTER
INSERT
    ON Family_ClubMember_Location FOR EACH ROW
BEGIN IF EXISTS (
    SELECT
        1
    FROM
        Family_ClubMember_Location
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

----------------------------------------------------------------------------------------------------------------------------------
CREATE TRIGGER validate_guardian_start_date AFTER
INSERT
    ON ClubMember_Guardians FOR EACH ROW
BEGIN IF EXISTS (
    SELECT
        1
    FROM
        ClubMember_Guardians
    WHERE
        clubMemberID = NEW.clubMemberID
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