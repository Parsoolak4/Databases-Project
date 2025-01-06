<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/search.css">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main class="main-content">
        <section class="section-search">
            <h1>Search Criteria</h1>
            <form action="/search" method="GET">
                <!-- Search Type Radio Buttons -->
                <div class="form-group">
                    <h2>Search For</h2>
                    <div class="search-type-container">
                        <label>
                            <input type="radio" name="searchType" value="person" onclick="updateSearchType()" id="searchPerson">
                            Person
                        </label>
                        <label>
                            <input type="radio" name="searchType" value="location" onclick="updateSearchType()" id="searchLocation">
                            Location
                        </label>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="form-group sort-container" id="sortContainer" style="display: none;">
                    <h2>Sort By</h2>
                    <select name="sortBy" id="sortBy">
                        <option value="firstName">First Name</option>
                        <option value="lastName">Last Name</option>
                        <option value="personID">Person ID</option>
                        <option value="locationName">Location Name</option>
                    </select>
                    <select name="sortOrder">
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                    <button type="submit" name="sort" value="Sort">Sort</button>
                </div>

                <!-- Person Type Radio Buttons (for Person search only) -->
                <div class="form-group" id="personTypeContainer" style="display: none;">
                    <h2>Select Person Type</h2>
                    <div class="person-type-container">
                        <label>
                            <input type="radio" name="personType" value="child" onclick="updateAttributes()" id="child">
                            Child
                        </label>
                        <label>
                            <input type="checkbox" name="personType" value="personnel" onclick="updateAttributes()" id="personnel">
                            Personnel
                        </label>
                        <label>
                            <input type="checkbox" name="personType" value="family" onclick="updateAttributes()" id="family">
                            Family Member
                        </label>
                    </div>
                </div>

                <!-- Checkboxes for Common Attributes -->
                <div class="form-group" id="commonAttributesContainer" style="display: none;">
                    <h2>Common Attributes</h2>
                    <div id="common-attributes-container" class="checkboxes-container"></div>
                </div>

                <!-- Checkboxes for Specific Attributes -->
                <div class="form-group" id="specificAttributesContainer" style="display: none;">
                    <h2>Specific Attributes</h2>
                    <div id="specific-attributes-container" class="checkboxes-container"></div>
                </div>

                <!-- Buttons -->
                <div class="form-group button-group">
                    <button type="submit" name="submit" value="submit">Submit</button>
                    <button type="reset" name="reset" value="Reset" onclick="resetForm()">Reset</button>
                </div>
            </form>
        </section>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>

<script>
    const commonAttributesPerson = ['personID', 'firstName', 'middleInitials', 'lastName', 'gender', 'phoneNumber', 'email', 'DOB'];
    const commonAttributesLocation = ['locationID', 'locationName', 'locationPhoneNumber', 'locationWebAddress', 'locationCapacity'];
    const specificAttributes = {
        child: ['primaryFamilyMemberID', 'relationship', 'teamID'],
        personnel: ['position', 'salary', 'mandate'],
        family: ['clubMemberID', 'clubMemberName']
    };

    const commonContainer = document.getElementById('common-attributes-container');
    const specificContainer = document.getElementById('specific-attributes-container');

    function updateSearchType() {
        const searchPerson = document.getElementById('searchPerson').checked;
        const searchLocation = document.getElementById('searchLocation').checked;

        document.getElementById('personTypeContainer').style.display = searchPerson ? 'block' : 'none';
        document.getElementById('sortContainer').style.display = 'block';
        document.getElementById('commonAttributesContainer').style.display = 'block';
        document.getElementById('specificAttributesContainer').style.display = searchPerson ? 'block' : 'none';

        updateAttributes();
    }

    function updateAttributes() {
        const searchPerson = document.getElementById('searchPerson').checked;
        const childRadio = document.getElementById('child');
        const personnelCheckbox = document.getElementById('personnel');
        const familyCheckbox = document.getElementById('family');

        // Clear existing checkboxes
        commonContainer.innerHTML = '';
        specificContainer.innerHTML = '';

        // Add common attributes based on search type
        const commonAttributes = searchPerson ? commonAttributesPerson : commonAttributesLocation;
        commonAttributes.forEach((attribute, index) => {
            const label = document.createElement('label');
            label.style.marginRight = '20px';
            label.style.display = 'inline-block';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = `feature[${index}]`;
            checkbox.value = attribute;

            label.appendChild(checkbox);
            label.appendChild(document.createTextNode(attribute.charAt(0).toUpperCase() + attribute.slice(1)));
            commonContainer.appendChild(label);
        });

        // Add specific attributes for person type
        if (searchPerson) {
            if (childRadio.checked) {
                personnelCheckbox.checked = false;
                familyCheckbox.checked = false;
                personnelCheckbox.disabled = true;
                familyCheckbox.disabled = true;
            } else {
                personnelCheckbox.disabled = false;
                familyCheckbox.disabled = false;
            }

            if (personnelCheckbox.checked || familyCheckbox.checked) {
                childRadio.checked = false;
                childRadio.disabled = true;
            } else {
                childRadio.disabled = false;
            }

            let selectedPersonTypes = [];
            if (childRadio.checked) {
                selectedPersonTypes.push('child');
            }
            if (personnelCheckbox.checked) {
                selectedPersonTypes.push('personnel');
            }
            if (familyCheckbox.checked) {
                selectedPersonTypes.push('family');
            }

            selectedPersonTypes.forEach(personType => {
                specificAttributes[personType].forEach((attribute, index) => {
                    const label = document.createElement('label');
                    label.style.marginRight = '20px';
                    label.style.display = 'inline-block';
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = `specificFeature[${index}]`;
                    checkbox.value = attribute;

                    label.appendChild(checkbox);
                    label.appendChild(document.createTextNode(attribute.charAt(0).toUpperCase() + attribute.slice(1)));
                    specificContainer.appendChild(label);
                });
            });
        }
    }

    function resetForm() {
        document.getElementById('searchPerson').checked = false;
        document.getElementById('searchLocation').checked = false;
        document.getElementById('child').checked = false;
        document.getElementById('personnel').checked = false;
        document.getElementById('family').checked = false;
        document.getElementById('child').disabled = false;
        document.getElementById('personnel').disabled = false;
        document.getElementById('family').disabled = false;
        document.getElementById('personTypeContainer').style.display = 'none';
        document.getElementById('sortContainer').style.display = 'none';
        document.getElementById('commonAttributesContainer').style.display = 'none';
        document.getElementById('specificAttributesContainer').style.display = 'none';
        commonContainer.innerHTML = '';
        specificContainer.innerHTML = '';
    }
</script>


</html>
