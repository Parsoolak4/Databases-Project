-- Get a report of all active club members who have never lost a game in which they
-- played in. A club member is considered to win a game if she/he has been assigned to a
-- game session and was assigned to the team that has a score higher than the score of the
-- other team. The club member must be assigned to at least one formation game session.
-- The list should include the club member’s membership number, first name, last name,
-- age, phone number, email and current location name. The results should be displayed
-- sorted in ascending order by location name then by club membership number
With
    Winning_Teams AS (
        SELECT
            CASE
                WHEN homeScore > visitingScore THEN homeTeamID
                WHEN homeScore < visitingScore THEN visitingTeamID
                ELSE NULL
            END AS teamID
        FROM
            Scheduled_Events
    )
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
    JOIN Teams t ON (t.teamID = tm.teamID)
    JOIN Locations l ON (t.locationID = l.locationID)
    JOIN Active_ClubMembers acm ON (acm.personID = tm.personID)
    LEFT JOIN Winning_Teams wt ON (tm.teamID = wt.teamID)
GROUP BY
    tm.personID
HAVING
    BIT_AND(wt.teamID IS NOT NULL)
ORDER BY l.name, acm.personID;