-- For a given period of time, give a report of the teams’ formations for all the locations.
-- For each location, the report should include the location name, the total number of
-- training sessions, the total number of players in the training sessions, the total number
-- of game sessions, the total number of players in the game sessions. Results should only
-- include locations that have at least three game sessions. Results should be displayed
-- sorted in descending order by the total number of game sessions. For example, the
-- period of time could be from Jan 1st, 2024, to March 31st, 2024
WITH
    TeamPlayerCount AS (
        SELECT
            t.*,
            SUM(tm.role <> 'coach') as totalPlayers
        FROM
            Teams t
            JOIN Team_Members tm ON (t.teamID = tm.teamID)
        GROUP BY
            teamID
    )
SELECT
    l.name,
    SUM(se.type = 'training') as numTrainingSess,
    SUM(se.type = 'game') as numGameSess,
    SUM((se.type = 'training') * tpc.totalPlayers) as numTrainingPlayers,
    SUM((se.type = 'game') * tpc.totalPlayers) as numGamePlayers
FROM
    Locations l
    JOIN Teams t ON (t.locationID = l.locationID)
    JOIN Scheduled_Events se ON (t.teamID IN (se.homeTeamID, se.visitingTeamID))
    JOIN TeamPlayerCount tpc ON (t.teamID = tpc.teamID)
WHERE
    DATE(se.date) BETWEEN '2024-07-31' AND '2024-08-31'
GROUP BY
    l.locationID
HAVING
    numGameSess >= 3
ORDER BY
    numGameSess DESC;