-- Get complete details for every location in the system. Details include address, city,
-- province, postal-code, phone number, web address, type (Head, Branch), capacity,
-- general manager name, and the number of club members associated with that location.
-- The results should be displayed sorted in ascending order by Province, then by city.
SELECT l.*, p.firstName as gmFirstName, p.lastName as gmLastName, count(acm.personID) as clubMemberCount FROM Locations l
JOIN Active_Personnel ap ON (l.locationID = ap.locationID AND ap.isGeneralManager)
JOIN Persons p ON (ap.personID = p.personID)
JOIN Active_ClubMembers acm ON (acm.locationID = l.locationID)
GROUP BY locationID
ORDER BY l.province, l.city;
