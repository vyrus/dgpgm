function renderTable(data, year) {
    console.info('Data', data);
    
    function million_formatter(amount) {
        amount = amount / 1000000;
        amount = Math.round(amount * 1000) / 1000;
        return amount.toString();
    }
    
    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        var restruct_data = [], total, idx, jdx, entry;
        var prop, props = ['financing', 'signed_gk_amount', 'signed_gk_num', 
                           'leftover_amount', 'leftover_num'];
        
        total = {id: '', title: 'Итого', total: true};
        
        for (idx in props) {
            prop = props[idx];
            total[prop] = 0;
        }                    
        
        for (idx in data) {
            entry = data[idx];
            restruct_data.push(entry);
            
            for (jdx in props) {
                prop = props[jdx];
                total[prop] += entry[prop] * 1;
            }
        }
        restruct_data.push(total);
        console.info('Restructured data', restruct_data);
        
        var store = new dojo.store.Memory({data: restruct_data, idProperty: 'id'});
        var dataStore = new dojo.data.ObjectStore({ objectStore: store });

        var struct = {
            cells: [],
            onBeforeRow: function(inDataIndex, inSubRows) {
                // Скрываем первую строку с колонками для задания ширины
                inSubRows[0].invisible = true;
            }
        };

        var row;

        /*
         * Первая строка - для задания ширины колонок
         * @link http://bugs.dojotoolkit.org/ticket/6591
         */
        row = [
            // Ширина для колонки "№" 
            {width: "30px"},
            // Ширина для колонки "Подпрограмма"
            {width: "280px"},
            // Плановое финансирование
            {width: "170px"},
            // Сумма финансирования
            {width: "160px"},
            // Количество
            {width: "90px"},
            // Сумма остатка
            {width: "90px"},
            // Количество
            {width: "90px"},
            
        ];
        struct.cells.push(row);
        
        row = [
            {
                name: '№',
                rowSpan: 2,
                field: 'id',
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Подпрограмма',
                rowSpan: 2,
                field: 'title',
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Плановое финансирование на ' + year + '. г., млн. руб.',
                rowSpan: 2,
                field: 'financing',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Заключенные до ' + year + ' г. контракты',
                colSpan: 2,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Остаток',
                colSpan: 2,
                headerClasses: 'staticHeader'
            }
        ];
        struct.cells.push(row);

        row = [
            {
                name: 'Сумма финансирования на ' + year + ' г., млн. руб.',
                field: 'signed_gk_amount',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'signed_gk_num',
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Сумма, млн. руб.',
                field: 'leftover_amount',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'leftover_num',  
                headerClasses: 'staticHeader'
            },
        ];
        struct.cells.push(row);
        console.info('Structure', struct);
        
        // create a new grid:
        var grid = new dojox.grid.DataGrid({
            query: {},
            store: dataStore,
            structure: struct,
            id: 'grid',
            
            // used to disable the click for sort on headers that are only for labeling
            onHeaderCellClick : function(e) { 
                return false;
                /*
                if (!dojo.hasClass(e.cell.id, "staticHeader"))
                {
                        e.grid.setSortIndex(e.cell.index);
                        e.grid.onHeaderClick(e);
                }*/
            }

        }, document.createElement('div'));

        // append the new grid to the div "gridContainer4":
        dojo.byId("gridContainer").appendChild(grid.domNode);

        // Устанавливаем обработчки события, чтобы поменять стиль строк с 
        // названиями подпрограмм
        dojo.connect(grid, "onStyleRow", function(row) {
            var grid = this;
            // get item
            var item = grid.getItem(row.index);          
                  
            if ('total' in item) {
                row.customStyles += "/* background-color: #f3f9ff; */ font-weight: bold"; 
            }
        }); 
        
        // Call startup, in order to render the grid:
        grid.startup();
    });
};