alert("plop");

var liste = [
    "Draggable",
    "Droppable",
    "Resizable",
    "Selectable",
    "Sortable"
];

$('#user').autocomplete({
    source : liste
});