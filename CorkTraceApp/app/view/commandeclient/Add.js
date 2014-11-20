Ext.define('CT.view.commandeclient.Add', {
    extend: 'Ext.window.Window',
    alias: 'widget.commandeclientadd',

    title: 'Add Cmd',
    layout: 'border',
    autoShow: true,
    width:"100%",
    height:"100%",

    initComponent: function() {
        
        this.items = [
            {
                xtype:"grid",
                region:"center",
                title:"Details",
                store:"CommandeClientDetails",
                id : "gridcommandeclientdetails",
                plugins: [ Ext.create('Ext.grid.plugin.CellEditing', {
                    clicksToEdit: 1
                })],
                columns : [
                    {header: 'Ccd Id',  dataIndex: 'ccd_id',  flex: 1},
                    {header: 'Ccl Id', dataIndex: 'ccl_id', flex: 1},
                    {header: 'Pro Id', dataIndex: 'pro_id', flex: 1},
                    {header: 'Nom', dataIndex: 'pro_nom', flex: 1},
                    {header: 'Taille', dataIndex: 'pro_taille', flex: 1},
                    {header: 'Qualite', dataIndex: 'pro_qualite', flex: 1},
                    {header: 'Prix', dataIndex: 'ccd_prix', flex: 1,
                    field: {
                        xtype: 'numberfield',
                        allowBlank: false,
                        minValue: 0,
                        maxValue: 100000
                    }},
                    {header: 'Qte', dataIndex: 'ccd_quantite', flex: 1,
                    field: {
                        xtype: 'numberfield',
                        allowBlank: false,
                        minValue: 0,
                        maxValue: 100000
                    }},
                    {header: 'Marquage', dataIndex: 'ccd_marquage', flex: 1,
                    field: {xtype: 'textfield'}}
                ]
            },{
                xtype:"grid",
                region:"south",
                height:"35%",
                id : "gridajoutproduit",
                title:"Ajouter un produit au details",
                store: 'Produits',
                columns : [
                    {header: 'Pro Id',  dataIndex: 'pro_id',  flex: 1},
                    {header: 'Nom', dataIndex: 'pro_nom', flex: 1},
                    {header: 'Taille', dataIndex: 'pro_taille', flex: 1},
                    {header: 'Qualite', dataIndex: 'pro_qualite', flex: 1}
                ]
            },{
                xtype:"form",
                id : "form_add_cmd_cli",
                region:"north",
                height:"20%",
                title:"Commande Client",
                layout : "vbox",
                items: [{
                    xtype:"panel",
                    layout: "hbox",
                    border:false,
                    defaults : {
                        margins : "10 10 10 10",
                        labelWidth : 150
                    },
                    items : [{
                        xtype:"datefield",
                        name: "ccl_dateLiv",
                        fieldLabel: "Date de livraison"
                    }]
                },{
                    xtype:"panel",
                    layout: "hbox",
                    border:false,
                    defaults : {
                        margins : "10 10 10 10",
                        labelWidth : 150
                    },
                    items:[{
                        xtype:"textfield",
                        name: "cli_id",
                        fieldLabel: "Id Client"
                    },{
                        xtype:"textfield",
                        name: "clc_id",
                        fieldLabel: "Id Commercial"
                    },{
                        xtype:"textfield",
                        name: "dpy_id",
                        fieldLabel: "Delai paiement"
                    },{
                        xtype:"textfield",
                        name: "cla_id",
                        fieldLabel: "Adresse livraison"
                    }]
                }]
            }
        ];

        this.buttons = [
            {
                text: 'Ajouter cette commande client',
                action: 'save',
                handler : function(){

                    console.log("debut loop");
                    var store = Ext.getCmp("gridcommandeclientdetails").getStore();

                    var details = new Array();
                    store.each(function(record,idx){

                        details.push({
                            "pro_id" : record.get("pro_id"),
                            "ccd_quantite" : record.get("ccd_quantite"),
                            "ccd_prix" : record.get("ccd_prix"),
                            "ccd_marquage" :  record.get("ccd_marquage")
                        });
                    });

                    var values = Ext.getCmp("form_add_cmd_cli").getForm().getValues();

                    var data = new Object();
                    data.ccl_dateLiv = values.ccl_dateLiv;
                    data.clc_id = values.clc_id;
                    data.dpy_id = values.dpy_id;
                    data.cla_id = values.cla_id;
                    data.details = details;

                    console.log(data);
                    console.log("End loop");

                    Ext.Ajax.request({
                        url: 'data/commande_client/create.php',
                        method: 'POST',          
                        waitTitle: 'Connecting',
                        waitMsg: 'Sending data...',                                     
                        params: {
                        "data" : JSON.stringify(data)
                        },
                        scope:this,
                        success: true,                                    
                        failure: function(){console.log('failure');}
                    });

                }
            },
            {
                text: 'Annuler',
                scope: this,
                handler: this.close
            }
        ];

        this.callParent(arguments);
    }
});