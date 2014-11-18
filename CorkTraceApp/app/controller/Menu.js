Ext.define('CT.controller.Menu', {
    extend: 'Ext.app.Controller',

    stores: ['Menu'],
	   
    views: [
    	'menu.List'
    ],
	   
    init: function() {
        this.control({
		    'menulist': {
		    	itemclick: this.showPanel
		    }
        });
    },

	showPanel : function(treeMenu, record, item, index, e, eOpts ){

		console.log("MENU");
		
		var name = record.get("text");
		var feed = Ext.getCmp("feedviewer");
		feed.removeAll(false);
		
		var pan = null;
		
		if( name == "Client" ){

            console.log("Client");
            pan = {xtype: "clientlist"};

        }else if( name == "Fournisseur" ){

   			console.log("Fournisseur");
   			pan = {xtype: "fournisseurlist"};

        }else{
            console.log("Aucun");
        }
		
		feed.add(pan);
	}

});