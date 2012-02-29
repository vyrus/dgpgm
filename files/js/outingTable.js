var store;
var dataStore;

function num_values(obj)
{
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function get_field_name(prop_name, year)
{
    if (year == "sum")
    {
        return prop_name;
    } else
    {
        return prop_name + '_' + year;
    }
}

function makeOut(data, statTitle)
{
console.info(data);
    var firstEntry;
    for (var i in data)
    {
        firstEntry = data[i];
        break;
    }

    var propertiesCnt = firstEntry.content.length;
    var yearsCnt = num_values(firstEntry.content[0].values);

    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        /* storage forming*/
        var dataForStorage = [], entry_idx, entry, entry_data, prop_idx, property,
            year, field_name, prop_sum;

        for (entry_idx in data)
        {
            entry = data[entry_idx];
            entry_data = {title: entry.title, type: entry.type};

            for (prop_idx in entry.content)
            {
                prop_sum = 0;
                property = entry.content[prop_idx];
                for (year in property.values)
                {
                    field_name = get_field_name(property.propTitle, year);
                    entry_data[field_name] = property.values[year];
                    prop_sum += parseInt(property.values[year] * 1);
                }
                field_name = get_field_name(property.propTitle, "sum");
                entry_data[field_name] = prop_sum;
            }

            dataForStorage.push(entry_data);
        }

        store = new dojo.store.Memory({data: dataForStorage, idProperty: "id"});
        dataStore = new dojo.data.ObjectStore({ objectStore: store });
        /*eo  storage forming*/

   /*structure making*/
    var struct = {
        cells: [],
        
        onBeforeRow : function(inDataIndex, inSubRows)
        {
                console.log("in onBeforeRow " + inDataIndex);
                // Всегда скрываем первую строку с колонками для задания ширины
                inSubRows[0].invisible = true;

                if (inDataIndex >= 0)
                {
                    
                    console.info(inSubRows[1]);
                    console.info(inSubRows[1].invisible);
                    console.info(inSubRows[1].rowSpan);
                    console.info("-----------");
                    console.info(inSubRows[1][0]);
                    console.info(inSubRows[1][0].invisible);
                    console.info(inSubRows[1][0].rowSpan);

                    inSubRows[1].invisible = true;
//                    inSubRows[2].invisible = true;

                    /*
                    inSubRows[1][1].customStyles.push("background: #808080;color:white;font-size:10pt;");
                    inSubRows[1][2].customStyles.push("background: #808080;color:white;font-size:10pt;");
                    inSubRows[2].customStyles += "color:blue;";
                    */

                    /*
                    console.info(inSubRows[1][1]);
                    console.info(inSubRows[1][1].customStyles);
                    console.info(inSubRows[2]);
                    */
                } else
                {
                    inSubRows[1].invisible = false;
                    inSubRows[2].invisible = false;
                }
        }
    };

    var row;

    /*
     * Первая строка - для задания ширины колонок
     * @link http://bugs.dojotoolkit.org/ticket/6591
     */
    row = [];
    // Ширина для колонки "Подпрограмма/Мероприятие"
    row.push({width: "350px"});

    // Определяем общее количество колонок со значениями показателей
    var num_value_cells = (yearsCnt + 1) * propertiesCnt;

    // Добавляем в первую строку нужное их количество
    for (var i = 0; i < num_value_cells; i++) {
        row.push({width: (yearsCnt > 1 ? "70px" : "100px")});
    }
    struct.cells.push(row);
    
    row = [
        { //first row
            name : statTitle,
            colSpan : (yearsCnt + (yearsCnt > 1) * 1) * propertiesCnt + 1,
            headerClasses : "staticHeader"
        }
    ];
    struct.cells.push(row);

    row = [
        { //second row
            name: 'Подпрограмма/Мероприятие',
            field: 'title',
            rowSpan: 2,
            headerClasses: "staticHeader"
        }
    ];

    var first_sp_props_values = firstEntry.content;
    for (var prop_val in first_sp_props_values)
    {
        var propTitle = first_sp_props_values[prop_val].propTitle;
        row.push({
            name: propTitle,
            colSpan: yearsCnt + (yearsCnt > 1) * 1,
            headerClasses: "staticHeader"
        });
    }

    struct.cells.push(row);

    row = [];
    var prop_idx, property, year, field_name;
    for (prop_idx in firstEntry.content)
    {
        property = firstEntry.content[prop_idx];
        for (year in property.values)
        {
            row.push({
                // third row
                name: year,
                headerClasses : "staticHeader2",
                field: get_field_name(property.propTitle, year)
            });
        }
        
        if (yearsCnt > 1) {
            row.push({
                // third row
                name: "Итого",
                field: get_field_name(property.propTitle, "sum")
            });
        }
    }
    struct.cells.push(row);
    /* eo structure making*/


        // create a new grid:
        var grid4 = new dojox.grid.DataGrid(
        {
                query : {},
                store : dataStore,
    //                        clientSort : true,
                rowSelector : '20px',
                structure : struct,
                id : "grid",
                // used to disable the click for sort on headers that are only for labeling
                onHeaderCellClick : function(e)
                { return false;
    /*
                        if (!dojo.hasClass(e.cell.id, "staticHeader"))
                        {
                                e.grid.setSortIndex(e.cell.index);
                                e.grid.onHeaderClick(e);
                        }*/
                }

        }, document.createElement('div'));

        // append the new grid to the div "gridContainer4":
        dojo.byId("gridContainer").appendChild(grid4.domNode);

        // Устанавливаем обработчки события, чтобы поменять стиль строк с 
        // названиями подпрограмм
        dojo.connect(grid4, "onStyleRow", function(row) {
            var grid = this;
            // get item
            var item = grid.getItem(row.index);          
                  
            if (item.type == 'subprogram') {
                row.customStyles += "background-color: #f3f9ff; font-weight: bold"; 
            }
        }); 
        
        // Call startup, in order to render the grid:
        grid4.startup();
    });
};