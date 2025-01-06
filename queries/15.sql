-- For a given location, get the list of all family members who have currently active club
-- members associated with them and are also head coaches for the same location.
-- Information includes first name, last name, and phone number of the family member.
-- A family member is considered to be a head coach if she/he is assigned as a head coach
-- to at least one team formation session in the same location.
SELECT
    p.firstName,
    p.lastName,
    p.telephoneNumber
FROM
    Active_Team_Members acm
    JOIN Teams t ON (acm.teamID = t.teamID)
    JOIN Active_ClubMember_Guardians acg ON (acm.personID = acg.guardianID)
    JOIN Active_Family_ClubMember_Location afcl ON (
        afcl.personID IN (acg.guardianID, acg.clubMemberID)
    )
    JOIN Persons p ON (acm.personID = p.personID)
WHERE
    acm.role = 'coach'
GROUP BY
    acm.personID
HAVING
    -- all registration ids are the same as the location of the head coach's team
    SUM(t.locationID != afcl.locationID) = 0;