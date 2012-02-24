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
    return prop_name + '_' + year;
}

function makeOut(data)
{
console.info(data);
    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        /* storage forming

         example:
         var employees = [
            {title:"spTitle/mTitle1", propTitle1_2012:"accounting"},
            {title:"spTitle/mTitle2", propTitle1_2013:"engineering"},
            {title:"spTitle/mTitle3", propTitle1_2014:"sales"},
            {title:"spTitle/mTitle4", propTitle1_2015:"sales"}
        ];
        */
        var dataForStorage = [], entry_idx, entry, entry_data, prop_idx, property,
            year, field_name, prop_sum;

        for (entry_idx in data)
        {
            entry = data[entry_idx];
            entry_data = {title: entry.title};

            for (prop_idx in entry.content)
            {
                prop_sum = 0;
                property = entry.content[prop_idx];
                /* if property has data per years*/
                if (isNaN(property.values))
                {
                    for (year in property.values)
                    {
                        field_name = get_field_name(property.propTitle, year);
                        entry_data[field_name] = property.values[year];
                        prop_sum += parseInt(property.values[year]);
                    }
                    field_name = get_field_name(property.propTitle, "sum");
                    entry_data[field_name] = prop_sum;
                } else
                {
                    /*property HASN'T data per years, it's a common property*/
                    field_name = property.propTitle;
                    entry_data[field_name] = property.values;
                }
            }

            dataForStorage.push(entry_data);
        }

        console.info('Data', dataForStorage);
        
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

                    //inSubRows[1].invisible = true;
                    //inSubRows[2].invisible = true;

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


    // Определяем общее количество колонок со значениями показетелей
    var num_value_cells = 0, firstEntry, propVal;
    /* 
     * Количество лет в периодических показателях и кол-во столбцов в них 
     * (годы + итого, если перечислены несколько лет
     */
    var num_years, num_cols_per_property;
    
    for (var i in data)
    {
        firstEntry = data[i];
        for (var j in firstEntry.content)
        {
            propVal = firstEntry.content[j];
            if (!isNaN(propVal.values))
            {
                num_value_cells++;
            } else
            {
                if (num_years == undefined) {
                    num_years = num_values(propVal.values);
                    num_cols_per_property = num_years + (num_years > 1 * 1);
                }
                num_value_cells += num_cols_per_property;
            }
        }
        break;
    }

    var row;

    /*
     * Нулевая строка - для задания ширины колонок
     * @link http://bugs.dojotoolkit.org/ticket/6591
     */
    row = [];
    //zero row
    // Ширина для колонки "Подпрограмма/Мероприятие"
    row.push({width: "350px"});

    // Добавляем в первую строку нужное их количество
    for (var prop_idx in firstEntry.content)
    {
        propVal = firstEntry.content[prop_idx];
        if (!isNaN(propVal.values))
        { // for common prop val
            row.push({width: "100px"});
        } else
        { // for years data
            num_years = num_values(propVal.values);
            for (var i = 0; i < num_cols_per_property; i++) {
                row.push({width: "55px"});
            }
        }
    }
    struct.cells.push(row);

    
    /*
    row = [
        { //first row
            name : ';)',
            colSpan : num_value_cells + 1,
            headerClasses : "staticHeader"
        }
    ];
    struct.cells.push(row);
    */
    
    row = [
        { //second row
            name: 'Подпрограмма/Мероприятие',
            field: 'title',
            rowSpan: 2,
            headerClasses: "staticHeader"
        }
    ];

    for (var prop_idx in firstEntry.content)
    {
        propVal = firstEntry.content[prop_idx];
        propTitle = propVal.propTitle;
        if (!isNaN(propVal.values))
        { // for common prop val
            row.push({
            name: propTitle,
            rowSpan: 2,
            field: propTitle,
            headerClasses: "staticHeader"
            });
        } else
        { // for years data
            num_years = num_values(propVal.values);
            
            row.push({
                name: propTitle,
                colSpan: num_cols_per_property,
                headerClasses: "staticHeader"
            });
        }
    }
    struct.cells.push(row);

    row = [];
    var prop_idx, property, year, field_name;
    for (var prop_idx in firstEntry.content)
    {
        propVal = firstEntry.content[prop_idx];
        if (!isNaN(propVal.values))
        { // for common prop val - there's no cells
        } else
        { // for years data
            for (year in propVal.values)
            {
                row.push({
                    // third row
                    name: year,
                    headerClasses : "staticHeader2",
                    field: get_field_name(propVal.propTitle, year)
                });
            }
            row.push({
                // third row
                name: "Итого",
                field: get_field_name(propVal.propTitle, "sum")
            });
        }
    }
    struct.cells.push(row);
    /* eo structure making*/


    console.info('Struct', struct);
    
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

        // Call startup, in order to render the grid:
        grid4.startup();
    });
};