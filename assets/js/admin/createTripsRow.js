export function createLine(tableRef, value, i) {
    const newRow = tableRef.insertRow(tableRef.rows.length);

    let newCell = newRow.insertCell(0);
    newCell.classList.add('center-align');
    newCell.classList.add(value[i].volunteerFullName ? 'bck-green' : 'bck-red');
    let newText = document.createTextNode(i + 1);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(1);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].departureName);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(2);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].arrivalName);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(3);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].date);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(4);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].hours);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(5);
    newCell.className = 'center-align';
    newText = document.createTextNode(value[i].beneficiaryFullName);
    newCell.appendChild(newText);

    newCell = newRow.insertCell(6);
    newCell.className = 'center-align';
    newText = document.createTextNode((value[i].volunteerFullName || 'Aucun'));
    newCell.appendChild(newText);
}
