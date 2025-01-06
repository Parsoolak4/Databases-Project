-- Get a report of all volunteer personnels who are not family members of any club
-- member. Results should include the volunteer’s first name, last name, telephone
-- number, email address, current location name and current role. Results should be
-- displayed sorted in ascending order by location name, then by role, then by first name
-- then by last name.
SELECT
    firstName,
    lastName,
    telephoneNumber,
    p.emailAddress,
    l.name AS locationName,
    pl.role
FROM
    Active_Personnel pl
    JOIN Persons p ON (p.personID = pl.personID)
    JOIN Locations l ON (pl.locationID = l.locationID)
WHERE
    pl.mandate = 'Volunteer'
    AND NOT EXISTS (
        SELECT
            guardianID
        FROM
            Active_ClubMember_Guardians
        WHERE
            guardianID = pl.personID
    )
ORDER BY l.name, pl.role, firstName, lastName;