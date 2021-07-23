
function downloadTable(container){
    tableId= '#' + container;
    $(tableId).btechco_excelexport({
        containerid: container,
        dataType: $datatype.Table,
    });
}