-- Get details of club members who are currently active and have been associated with at
-- least four different locations and are members for at most two years. Details include
-- Club membership number, first name and last name. Results should be displayed sorted
-- in ascending order by club membership number.
SELECT
    acm.personID, firstName, lastName
FROM
    Active_ClubMembers acm
    JOIN Family_ClubMember_Location fcl ON (fcl.personID = acm.personID)
GROUP BY
    acm.personID
HAVING
    COUNT(DISTINCT fcl.locationID) >= 4
    AND TIMESTAMPDIFF (
        YEAR,
        MIN(startDate),
        MAX(COALESCE(endDate, CURRENT_DATE))
    ) <= 3
ORDER BY acm.personID;