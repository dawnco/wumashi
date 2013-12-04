function btcTask(){



    jQuery("#tableBuyList").jqGrid({
        url: '/index.php/ajax/data/buy',
        datatype: "json",
        loadui: "disable",
        jsonReader: {
            root: "rows",
            repeatitems: false
        },
        height: 300,
        rowNum: 100,
        colNames: ['买入价', '买入量(BTC)', '兑换额(CNY)', '单数'],
        colModel: [
            {name: 'price', index: 'price', sortable: false, width: 80, sorttype: "float", align: "right", formatter: 'currency', formatoptions: {decimalPlaces: 2}},
            {name: 'number', index: 'number', sortable: false, width: 100, align: "right", sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 4, thousandsSeparator: ""}},
            {name: 'total', index: 'total', sortable: false, width: 100, align: "right", sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 2, thousandsSeparator: ""}},
            {name: 'count', index: 'count', sortable: false, width: 35, align: "right", sorttype: "float"}
        ],
        multiselect: false,
        caption: "&nbsp;比特币买单",
        autowidth: true
    });
    jQuery("#tableSaleList").jqGrid({
        url: '/index.php/ajax/data/sale',
        datatype: "json",
        loadui: "disable",
        jsonReader: {
            root: "rows",
            repeatitems: false
        },
        height: 300,
        rowNum: 100,
        colNames: ['卖出价', '卖出量(BTC)', '兑换额(CNY)', '单数'],
        colModel: [
           {name: 'price', index: 'price', sortable: false, width: 80, sorttype: "float", align: "right", formatter: 'currency', formatoptions: {decimalPlaces: 2}},
            {name: 'number', index: 'number', sortable: false, width: 100, align: "right", sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 4, thousandsSeparator: ""}},
            {name: 'total', index: 'total', sortable: false, width: 100, align: "right", sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 2, thousandsSeparator: ""}},
            {name: 'count', index: 'count', sortable: false, width: 35, align: "right", sorttype: "float"}
        ],
        multiselect: false,
        caption: "&nbsp;比特币卖单",
        autowidth: true
    });


    jQuery("#tableHistoryList").jqGrid({
        url: '/index.php/ajax/data/history',
        datatype: "json",
        loadui: "disable",
        jsonReader: {
            root: "rows",
            repeatitems: false
        },
        height: 300,
        rowNum: 100,
        colNames: ['日期', '交易类型', '成交价', '成交量(BTC)', '成交量(CNY)'],
        colModel: [
            {name: 'date', index: 'date', width: 140, sortable: false, align: 'center', sorttype: "date", formatter: 'date', formatoptions: {srcformat: 'ISO8601Long', newformat: 'Y-m-d H:i:s'}},
            {name: 'type', index: 'type', width: 88, sortable: false, align: 'center', sorttype: "int"},
            {name: 'price', index: 'price', width: 150, sortable: false, align: 'center', sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 2, thousandsSeparator: ""}},
            {name: 'number', index: 'number', width: 140, sortable: false, align: 'center', sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 4, thousandsSeparator: ""}},
            {name: 'total', index: 'total', width: 140, sortable: false, align: 'center', sorttype: "float", formatter: 'currency', formatoptions: {decimalPlaces: 2, thousandsSeparator: ""}}
        ],
        multiselect: false,
        caption: "&nbsp;比特币成交单据",
        autowidth: true
    });

   

}

$(document).ready(function() {
    btcTask();
    setInterval(function(){
        jQuery("#tableBuyList").trigger("reloadGrid");
         jQuery("#tableSaleList").trigger("reloadGrid");
          jQuery("#tableHistoryList").trigger("reloadGrid");


    },5000);

});

        