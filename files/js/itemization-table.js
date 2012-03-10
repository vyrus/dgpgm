function renderItemizationTable(data, container) {
    console.info('Data', data);
    
    function subprogram_link_formatter(title) {
        if ('no_link' in title) {
            return title.title;
        }
        return  '<a target="_blank" href="/stats/finance-program/' + title.id + '/">' + title.title + '</a>';
    }
    
    require(["dojo/store/Memory","dojo/data/ObjectStore","dojox/grid/DataGrid", "dojo/domReady!"], function() {
        
        var store = new dojo.store.Memory({data: data, idProperty: 'id'});
        var dataStore = new dojo.data.ObjectStore({ objectStore: store });

        var struct = {
            cells: [],
        };
        
        var row, row_num = 1;
        row = [
            {
                name: '№',
                width: '50px',
                field: 'id',
                get: function() { return row_num++; }
            },
            
            {
                name: 'Номер ГК',
                width: '50px',
                field: 'id',
            },
            
            {
                name: 'Дата заключения ГК',
                width: '100px',
                field: 'signing_date'
            },
            
            {
                name: 'Мероприятие',
                width: '300px',
                field: 'title'                
            },
            
            {
                name: 'Шифр заявки',
                width: '50px',
                field: 'cifer'
            },
            
            {
                name: 'Наименование работы',
                width: '200px',
                field: 'work_title',
                get: getCellVal,
                formatter: function(data) {
                	sum = data.sum;
                	sum = sum / 1000;
                    sum = Math.round(sum * 100) / 100;
                	
                    return '<a href="">' + data.work_title + '</a><br />' +
                	       'Cумма: ' + sum + ' тыс. руб.<br />' + 
                	       'Платёжные поручения: ' + data.orders_amount;
                }
            },
            
            {
                name: 'Наименование организации',
                width: '200px',
                field: 'id'
            },
            
            {
                name: 'Дата окончания работ',
                width: '100px',
                field: 'finish_date'
            },
            
            {
                name: 'Согласование',
                width: '100px',
                field: 'matching_organization'
            },
            
            {
                name: 'Файлы',
                width: '100px',
                field: 'id'
            }            
        ];
        struct.cells.push(row);
        console.info('Structure', struct);
        
        // create a new grid:
        var grid = new dojox.grid.DataGrid({
            query: {},
            store: dataStore,
            structure: struct,
            id: 'grid_itemization'
        }, document.createElement('div'));

        // append the new grid to the div:
        dojo.byId(container).appendChild(grid.domNode);

        // Call startup, in order to render the grid:
        grid.startup();
        
        function getCellVal(inRowIndex) 
        {
        	var item = grid.getItem(inRowIndex);
        	return {work_title: item.work_title, sum: item.sum, orders_amount: item.orders_amount};
        }
    });
};


function million_formatter(amount) {
    amount = amount / 1000000;
    amount = Math.round(amount * 1000) / 1000;
    return amount.toString();
}