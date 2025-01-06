<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Records Page</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/modifyRecords.css">
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main class="main-content">
        <section class="section-modify">
            <h1>Modify Records</h1>
            <form action="/modifyRecords" method="POST">
                <!-- Modify Type Radio Buttons -->
                <div class="form-group">
                    <h2>Modify Type</h2>
                    <div class="modify-type-container">
                        <label>
                            <input type="radio" name="modifyType" value="add" onclick="updateModifyType()" id="addRecord">
                            Add Record
                        </label>
                        <label>
                            <input type="radio" name="modifyType" value="update" onclick="updateModifyType()" id="updateRecord">
                            Update Record
                        </label>
                        <label>
                            <input type="radio" name="modifyType" value="delete" onclick="updateModifyType()" id="deleteRecord">
                            Delete Record
                        </label>
                    </div>
                </div>

                <!-- Record Type Radio Buttons -->
                <div class="form-group" id="recordTypeContainer" style="display: none;">
                    <h2>Record Type</h2>
                    <div class="record-type-container">
                        <label>
                            <input type="radio" name="recordType" value="person" onclick="updateAttributes()" id="recordPerson">
                            Person
                        </label>
                        <label>
                            <input type="radio" name="recordType" value="location" onclick="updateAttributes()" id="recordLocation">
                            Location
                        </label>
                    </div>
                </div>

                <!-- Person Type Radio/Checkboxes -->
                <div class="form-group" id="personTypeContainer" style="display: none;">
                    <h2>Person Type</h2>
                    <div class="person-type-container">
                        <label>
                            <input type="radio" name="personType" value="clubMember" id="clubMember" onclick="updateAttributes()">
                            Club Member
                        </label>
                        <label>
                            <input type="checkbox" name="personType[]" value="personnel" id="personnel" onclick="updateAttributes()">
                            Personnel
                        </label>
                        <label>
                            <input type="checkbox" name="personType[]" value="familyMember" id="familyMember" onclick="updateAttributes()">
                            Family Member
                        </label>
                    </div>
                </div>

                <!-- Form Fields for Add/Update/Delete -->
                <div class="form-group" id="formFieldsContainer" style="display: none;">
                    <h2>Record Details</h2>
                    <div id="form-fields-container"></div>
                </div>

                <!-- Buttons -->
                <div class="form-group button-group">
                    <button type="submit" name="submit" value="Submit">Submit</button>
                    <button type="reset" name="reset" value="Reset" onclick="resetForm()">Reset</button>
                </div>
            </form>
        </section>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
</body>

<script>
    const personAttributes = ['personID', 'firstName', 'middleInitials', 'lastName', 'gender', 'phoneNumber', 'email', 'DOB'];
    const locationAttributes = ['locationID', 'locationName', 'locationPhoneNumber', 'locationWebAddress', 'locationCapacity'];
    const specificAttributes = {
        clubMember: ['primaryFamilyMemberID', 'relationship', 'teamID'],
        personnel: ['position', 'salary', 'mandate'],
        familyMember: ['clubMemberID', 'clubMemberName']
    };

    const formFieldsContainer = document.getElementById('form-fields-container');

    function updateModifyType() {
        const addRecord = document.getElementById('addRecord').checked;
        const updateRecord = document.getElementById('updateRecord').checked;
        const deleteRecord = document.getElementById('deleteRecord').checked;

        document.getElementById('recordTypeContainer').style.display = (addRecord || updateRecord || deleteRecord) ? 'block' : 'none';
        document.getElementById('formFieldsContainer').style.display = (addRecord || updateRecord || deleteRecord) ? 'block' : 'none';

        updateAttributes();
    }

    function updateAttributes() {
        const recordPerson = document.getElementById('recordPerson').checked;
        const recordLocation = document.getElementById('recordLocation').checked;
        const addRecord = document.getElementById('addRecord').checked;
        const deleteRecord = document.getElementById('deleteRecord').checked;

        document.getElementById('personTypeContainer').style.display = recordPerson && (addRecord || updateRecord) ? 'block' : 'none';
        formFieldsContainer.innerHTML = '';

        let selectedAttributes = [];
        if (recordPerson) {
            if (deleteRecord) {
                selectedAttributes = ['personID'];
            } else if (addRecord || updateRecord) {
                selectedAttributes = personAttributes;
                const childRadio = document.getElementById('clubMember');
                const personnelCheckbox = document.getElementById('personnel');
                const familyCheckbox = document.getElementById('familyMember');

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
                    selectedPersonTypes.push('clubMember');
                }
                if (personnelCheckbox.checked) {
                    selectedPersonTypes.push('personnel');
                }
                if (familyCheckbox.checked) {
                    selectedPersonTypes.push('familyMember');
                }

                selectedPersonTypes.forEach(personType => {
                    selectedAttributes = selectedAttributes.concat(specificAttributes[personType]);
                });
            }
        } else if (recordLocation) {
            if (deleteRecord) {
                selectedAttributes = ['locationID'];
            } else if (addRecord || updateRecord) {
                selectedAttributes = locationAttributes;
            }
        }

        selectedAttributes.forEach((attribute, index) => {
            const label = document.createElement('label');
            label.style.marginRight = '20px';
            label.style.display = 'inline-block';
            const input = document.createElement('input');
            input.type = 'text';
            input.name = attribute;
            input.placeholder = attribute.charAt(0).toUpperCase() + attribute.slice(1);

            label.appendChild(document.createTextNode(attribute.charAt(0).toUpperCase() + attribute.slice(1) + ': '));
            label.appendChild(input);
            formFieldsContainer.appendChild(label);
        });
    }

    function resetForm() {
        document.getElementById('addRecord').checked = false;
        document.getElementById('updateRecord').checked = false;
        document.getElementById('deleteRecord').checked = false;
        document.getElementById('recordPerson').checked = false;
        document.getElementById('recordLocation').checked = false;
        document.getElementById('clubMember').checked = false;
        document.getElementById('personnel').checked = false;
        document.getElementById('familyMember').checked = false;
        document.getElementById('clubMember').disabled = false;
        document.getElementById('personnel').disabled = false;
        document.getElementById('familyMember').disabled = false;
        document.getElementById('recordTypeContainer').style.display = 'none';
        document.getElementById('formFieldsContainer').style.display = 'none';
        document.getElementById('personTypeContainer').style.display = 'none';
        formFieldsContainer.innerHTML = '';
    }
</script>
</html>
