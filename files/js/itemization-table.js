function renderItemizationTable(data, container) {
    console.info('Data', data);
    
    function subprogram_link_formatter(title) {
        if ('no_link' in title) {
            return title.title;
        }
        return  '<a target="_blank" href="/stats/finance-program/' + title.id + '/">' + title.title + '</a>';
    }
    
    require(["dojo/store/Memory","dojo/data/ObjectStore", "dojox/grid/EnhancedGrid", "dojox/grid/enhanced/plugins/Pagination", "dojo/domReady!"], function() {
        
        var store = new dojo.store.Memory({data: data, idProperty: 'id'});
        var dataStore = new dojo.data.ObjectStore({ objectStore: store });

        var struct = {
            cells: [],
        };
        
        var row, row_num = 1;
        row = [
            {
                name: '№ п/п',
                width: '30px',
                field: 'id',
                get: function() { return row_num++; }
            },
            
            {
                name: '№ ГК',
                width: '50px',
                field: 'number',
            },
            
            {
                name: 'Дата заключения ГК',
                width: '80px',
                field: 'signing_date'
            },
            
            {
                name: 'Мероприятие',
                width: '250px',
                field: 'title',
                get: function(inRowIndex) 
                {
                	var item = grid.getItem(inRowIndex);
                	return item.mid + ' ' + item.title;
                }
            },
            
            {
                name: 'Шифр заявки',
                width: '50px',
                field: 'cifer'
            },
            
            {
                name: 'Наименование работы',
                width: '250px',
                field: 'work_title',
                get: getCellVal,
                formatter: function(data) {
                	sum = data.sum;
                	sum = sum / 1000;
                    sum = Math.round(sum * 100) / 100;
                	
                    return '<a href="/gk/gk/' + data.id + '/">' + data.work_title + '</a><br />' +
                	       'Cумма: ' + sum + ' тыс. руб.<br />' + 
                	       'Платёжные поручения: ' + data.orders_amount;
                }
            },
            
            {
                name: 'Наименование организации',
                width: '200px',
                field: 'full_title'
            },
            
            {
                name: 'Дата окончания работ',
                width: '80px',
                field: 'finish_date'
            },
            
            {
                name: 'Согласование',
                width: '250px',
                field: 'matching_organization'
            },
            
            {
                name: 'Файлы',
                width: '50px'
            }            
        ];
        struct.cells.push(row);
        console.info('Structure', struct);
        
        // create a new grid:
        var grid = new dojox.grid.EnhancedGrid({
            query: {},
            store: dataStore,
            structure: struct,
            id: 'grid_itemization',
            onHeaderCellClick : function(e)
            { return false;},
            
            ///*
            plugins: {
                pagination: {
                    pageSizes: ["5", "10", "14"],
                    description: true,
                    sizeSwitch: true,
                    pageStepper: true,
                    gotoButton: true,
                            // page step to be displayed
                    maxPageStep: 4,
                            // position of the pagination bar
                    position: "bottom"
                }
              }
			//*/
        }, document.createElement('div'));

        // append the new grid to the div:
        dojo.byId(container).appendChild(grid.domNode);

        // Call startup, in order to render the grid:
        grid.startup();
        
        function getCellVal(inRowIndex) 
        {
        	var item = grid.getItem(inRowIndex);
        	return {work_title: item.work_title, sum: item.sum, orders_amount: item.orders_amount, id:item.id};
        }
    });
};


function million_formatter(amount) {
    amount = amount / 1000000;
    amount = Math.round(amount * 1000) / 1000;
    return amount.toString();
}