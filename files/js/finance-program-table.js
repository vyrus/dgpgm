function renderProgramTable(data, year, container) {
    console.info('Data', data);
    
    function subprogram_link_formatter(title) {
        if ('no_link' in title) {
            return title.title;
        }
        return  '<a target="_blank" href="/stats/finance-program/' + title.id + '/">' + title.title + '</a>';
    }
	
	function subprogram_link_formatter1(signed_gk_num) {
        if ('no_link' in signed_gk_num) {
            return signed_gk_num.signed_gk_num;
        }
		return  '<a href="/stats/finance-program/gk/' + signed_gk_num.id + '/">' + signed_gk_num.signed_gk_num + '</a>';
    }
	
	function subprogram_link_formatter2(signed_gk_amount) {
        if ('no_link' in signed_gk_amount) {
		    signed_gk_amount.signed_gk_amount = signed_gk_amount.signed_gk_amount / 1000000;
			signed_gk_amount.signed_gk_amount = Math.round(signed_gk_amount.signed_gk_amount * 1000) / 1000;
			return signed_gk_amount.signed_gk_amount.toString();
           }
		signed_gk_amount.signed_gk_amount = signed_gk_amount.signed_gk_amount / 1000000;
		signed_gk_amount.signed_gk_amount = Math.round(signed_gk_amount.signed_gk_amount * 1000) / 1000;
		return  '<a href="/stats/finance-program/summa/' + signed_gk_amount.id + '/">' + signed_gk_amount.signed_gk_amount + '</a>';
    }
    
    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        var restruct_data = [], total, idx, jdx, entry;
        var prop, props = ['financing', 'signed_gk_amount', 'signed_gk_num', 
                           'leftover_amount', 'leftover_num'];
        
        total = {id: '', title: {no_link: true, title: 'Итого'}, 
		signed_gk_num: {no_link: true, signed_gk_num: 'Итого'},
		signed_gk_amount: {no_link: true, signed_gk_amount: 'Итого'},		
		total: true};
        
        for (idx in props) {
            prop = props[idx];
            if (prop=='signed_gk_num') total.signed_gk_num.signed_gk_num=0; else 
			if (prop=='signed_gk_amount') total.signed_gk_amount.signed_gk_amount=0; else 
			total[prop] = 0;
        }                    
        
        for (idx in data) {
            entry = data[idx];
            entry.title = {id: entry.id, title: entry.title};
			entry.signed_gk_num = {id: entry.id, signed_gk_num: entry.signed_gk_num};
			entry.signed_gk_amount = {id: entry.id, signed_gk_amount: entry.signed_gk_amount};
            restruct_data.push(entry);
            
            for (jdx in props) {
                prop = props[jdx];
                if (prop=='signed_gk_num') total.signed_gk_num.signed_gk_num += entry.signed_gk_num.signed_gk_num * 1;
				else if (prop=='signed_gk_amount') total.signed_gk_amount.signed_gk_amount += entry.signed_gk_amount.signed_gk_amount * 1;
				else total[prop] += entry[prop] * 1;
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

            {width: "400px"},
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
                formatter: subprogram_link_formatter,
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
                formatter: subprogram_link_formatter2, //million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'signed_gk_num',
				formatter: subprogram_link_formatter1,
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
            id: 'grid_program',
            
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

        // append the new grid to the div:
        dojo.byId(container).appendChild(grid.domNode);

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
        styleHiddenCells();        
    });
    
    styleHiddenCells();
};

function renderSubprogramTable(data, year, container) {
    console.info('Data', data);
    
    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        var restruct_data = [], total, idx, jdx, entry;
        var prop, props = ['plan_financing', 'plan_gk_count', 'tenders_amount', 
                           'tenders_num', 'gk_amount', 'gk_num', 'economy'];
        
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
            {width: "40px"},
            // Ширина для колонки "Мероприятие"
            {width: "280px"},
            // Плановая сумма финансирования
            {width: "70px"},
            // Плановое количество госконтрактов
            {width: "90px"},
            // Сумма финансирования объявленных конкурсов
            {width: "90px"},
            // Количество объявленных конкурсов
            {width: "90px"},
            // Сумма финансирования заключенных госконтрактов
            {width: "90px"},
            // Количество заключенных госконтрактов
            {width: "90px"},
            // Экономия
            {width: "75px"},
            
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
                name: 'Мероприятие',
                rowSpan: 2,
                field: 'title',
                headerClasses: 'staticHeader'
            },
            
            {
            name: 'План',
                colSpan: 2,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Объявлено конкурсов',
                colSpan: 2,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Заключено ГК',
                colSpan: 2,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Экономия, млн. руб.',
                rowSpan: 2,
                field: 'economy',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            }
        ];
        struct.cells.push(row);

        row = [
            {
                name: 'Сумма, млн. руб.',
                field: 'plan_financing',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'plan_gk_count',
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Сумма на ' + year + ' г., млн. руб.',
                field: 'tenders_amount',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'tenders_num',  
                headerClasses: 'staticHeader'
            },
            {
                name: 'Сумма на ' + year + ' г., млн. руб.',
                field: 'gk_amount',
                formatter: million_formatter,
                headerClasses: 'staticHeader'
            },
            
            {
                name: 'Количество',
                field: 'gk_num',  
                headerClasses: 'staticHeader'
            }
        ];
        struct.cells.push(row);
        console.info('Structure', struct);
        
        // create a new grid:
        var grid = new dojox.grid.DataGrid({
            query: {},
            store: dataStore,
            structure: struct,
            id: 'grid_subprogram',
            
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

        // append the new grid to the div:
        dojo.byId(container).appendChild(grid.domNode);

        // Устанавливаем обработчки события, чтобы поменять стиль итоговой строки
        dojo.connect(grid, "onStyleRow", function(row) {
            var grid = this;
            // get item
            var item = grid.getItem(row.index);          
                  
            if ('total' in item) {
                row.customStyles += "/* background-color: #f3f9ff; */ font-weight: bold"; 
            }
            
            grid.focus.styleRow(row);
            grid.edit.styleRow(row);
        }); 
        
        // Call startup, in order to render the grid:
        grid.startup();
        styleHiddenCells();
    });
};

function million_formatter(amount) {
    amount = amount / 1000000;
    amount = Math.round(amount * 1000) / 1000;
    return amount.toString();
}

function styleHiddenCells()
{
	dojo.query("td.dojoxGridCell[colspan=2]").style("border","0px");
	dojo.query("td.dojoxGridCell[colspan=2]").style("padding","0px");
}