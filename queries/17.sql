-- Get a report of all the personnels who were president of the club at least once or is
-- currently president of the club. The report should include the president’s first name,
-- last name, start date as a president and last date as president. If last date as president is
-- null means that the personnel is the current president of the club. Results should be
-- displayed sorted in ascending order by first name then by last name then by start date
-- as a president.
SELECT
    p.firstName, p.lastName, pl.startDate, pl.endDate
FROM
    Personnel pl
    JOIN Locations l ON (pl.locationID = l.locationID)
    JOIN Persons p ON (pl.personID = p.personID)
WHERE
    pl.isGeneralManager
    AND l.type = 'Head'
ORDER BY p.firstName, p.lastName, pl.startDate