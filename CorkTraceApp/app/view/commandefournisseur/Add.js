Ext.define('CT.view.commandefournisseur.Add', {
    extend: 'Ext.window.Window',
    alias: 'widget.commandefournisseuradd',

    title: 'Add Cmd',
    layout: 'border',
    autoShow: true,
    width:"100%",
    height:"100%",
    id: "window_commandefou_add",
    closable:false,

    initComponent: function() {
        
        this.items = [
            {
                xtype:"grid",
                region:"center",
                title:"Details",
                store:"CommandeFournisseurDetails",
                id : "gridcommandefournisseurdetails",
                plugins: [ Ext.create('Ext.grid.plugin.CellEditing', {
                    clicksToEdit: 1
                })],
                columns : [
                    {header: 'Pro Id',  dataIndex: 'pro_id',  flex: 1},
                    {header: 'Nom', dataIndex: 'pro_nom', flex: 1},
                    {header: 'Taille', dataIndex: 'pro_taille', flex: 1},
                    {header: 'Qualite', dataIndex: 'pro_qualite', flex: 1},
                    {header: 'Qte', dataIndex: 'cfd_quantite', flex: 1,
                    field: {
                        xtype: 'numberfield',
                        allowBlank: false,
                        minValue: 0,
                        maxValue: 99999999
                    }},
                    {header: 'Prix', dataIndex: 'cfd_prix', flex: 1,
                    field: {
                        xtype: 'numberfield',
                        allowBlank: false,
                        minValue: 0,
                        maxValue: 99999999
                    }}
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
                id : "form_add_cmd_fou",
                region:"north",
                height:"15%",
                title:"Commande fournisseur",
                layout : "hbox",
                defaults : {
                        margins : "10 10 10 10",
                        labelWidth : 150
                },
                items: [{
                    xtype: "combobox",
                    name : 'fou_id',
                    fieldLabel: 'Choisir fournisseur',
                    displayField: 'fou_nom',
                    valueField: 'fou_id',
                    store: 'Fournisseurs',
                    queryMode: 'local',
                    editable:false,
                    listeners: {
                            'afterrender': function(cb, eOpts){
                                cb.getStore().load();
                            }
                    }
                },{
                    xtype:"datefield",
                    name: "cfo_datecmd",
                    fieldLabel: "Date de commmande",
                    editable:false,
                    format : "d/m/Y"
                }]
            }
        ];

        this.buttons = [
            {
                text: 'Ajouter cette commande fournisseur',
                action: 'save',
                handler : function(){

                    var store = Ext.getCmp("gridcommandefournisseurdetails").getStore();

                    var details = new Array();
                    store.each(function(record,idx){

                        details.push({
                            "pro_id" : record.get("pro_id"),
                            "cfd_quantite" : record.get("cfd_quantite"),
                            "cfd_prix" : record.get("cfd_prix")
                        });
                    });

                    var values = Ext.getCmp("form_add_cmd_fou").getForm().getValues();

                    var data = new Object();
                    data.fou_id = values.fou_id;
                    data.cfo_datecmd = values.cfo_datecmd;
                    data.details = details;

                    Ext.Ajax.request({
                        url: 'data/commande_fournisseur/create.php',
                        method: 'POST',          
                        waitTitle: 'Connecting',
                        waitMsg: 'Sending data...',                                     
                        params: {
                        "data" : JSON.stringify(data)
                        },
                        scope:this,
                        success: function(response, opts) {
   
                            Ext.getCmp("commandefournisseurlist").getStore().reload();
                            Ext.getCmp("commandefournisseurlist").getView().refresh();
                            Ext.getCmp("gridcommandefournisseurdetails").getStore().removeAll();
                            Ext.getCmp("window_commandefou_add").close();

                        },                            
                        failure: function(){
                            alert("Echec ajout");
                            Ext.getCmp("window_commandefou_add").close();
                        }
                    });


                }
            },
            {
                text: 'Annuler',
                scope: this,
                handler: function(){

                    Ext.getCmp("gridcommandefournisseurdetails").getStore().removeAll();
                    Ext.getCmp("window_commandefou_add").close();
                }
            }
        ];

        this.callParent(arguments);
    }
});