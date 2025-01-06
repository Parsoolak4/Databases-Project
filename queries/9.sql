-- For a given location and day, get details of all the teams formations recorded in the
-- system. Details include, head coach first name and last name, start time of the training
-- or game session, address of the session, nature of the session (training or game), the
-- teams name, the score (if the session is in the future, then score will be null), and the
-- first name, last name and role (goalkeeper, defender, etc.) of every player in the team.
-- Results should be displayed sorted in ascending order by the start time of the session.
SELECT
    t.name,
    se.date,
    se.address,
    se.type,
    CASE
        WHEN se.homeTeamID = t.teamID THEN se.homeScore
        ELSE se.visitingScore
    END AS score,
    firstName,
    lastName,
    tm.role
FROM
    Scheduled_Events se
    JOIN Teams t ON (t.teamID in (se.homeTeamID, se.visitingTeamID))
    JOIN Active_Team_Members tm ON (t.teamID = tm.teamID)
    JOIN Persons p ON (tm.personID = p.personID)
WHERE
    t.locationID = 2
    AND DATE (se.date) = '2024-07-17';