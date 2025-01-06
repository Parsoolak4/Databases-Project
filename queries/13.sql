-- Get a report of all active club members who have only been assigned as goalkeepers in
-- all the formation team sessions they have been assigned to. They must be assigned to
-- at least one formation session as a goalkeeper. They should have never been assigned
-- to any formation session with a role different than goalkeeper. The list should include
-- the club member’s membership number, first name, last name, age, phone number,
-- email and current location name. The results should be displayed sorted in ascending
-- order by location name then by club membership number.
SELECT
    p.personID, p.firstName, p.lastName,
    TIMESTAMPDIFF(YEAR, p.dateOfBirth, CURRENT_DATE) as age,
    p.telephoneNumber, p.emailAddress, l.name as locationName
FROM
    Active_Team_Members atm
JOIN Active_ClubMembers p ON (atm.personID = p.personID)
JOIN Locations l ON (p.locationID = l.locationID)
GROUP BY
    atm.personID
HAVING
    BIT_AND(atm.role = 'goalkeeper')
ORDER BY locationName, p.personID;