-- Get a report of all active club members who have only been assigned at least once to
-- every role throughout all the formation team game sessions. The club member must be
-- assigned to at least one formation game session as a goalkeeper, one as a defender, one
-- as a midfielder, and one as a forward. The list should include the club member’s
-- membership number, first name, last name, age, phone number, email and current
-- location name. The results should be displayed sorted in ascending order by location
-- name then by club membership number.
SELECT
    acm.personID,
    acm.firstName,
    acm.lastName,
    TIMESTAMPDIFF (YEAR, acm.dateOfBirth, CURRENT_DATE) as age,
    acm.telephoneNumber,
    acm.emailAddress,
    l.name as locationName
FROM
    Team_Members tm
    JOIN Active_ClubMembers acm ON (tm.personID = acm.personID)
    JOIN Locations l ON (acm.locationID = l.locationID)
GROUP BY
    acm.personID
HAVING
    /* Matches bitmask 01110 */
    SUM(1 << tm.role) & 14 = 14;