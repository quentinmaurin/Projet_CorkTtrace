Ext.define('CT.controller.Fournisseurs', {
    extend: 'Ext.app.Controller',

    stores: ['Fournisseurs', 'TypeFournisseurs'],
	
	models: ['Fournisseur', 'TypeFournisseur'],
	   
    views: [
    	'fournisseur.List',
		'fournisseur.Edit',
		'fournisseur.Add'
    ],
	   
    init: function() {
        this.control({
	        'fournisseuredit button[action=save]': {
	        	click: this.updateFournisseur
	        },
	        'fournisseuradd button[action=save]': {
	        	click: this.createFournisseur
	        }
        });
    },
	
    updateFournisseur: function(button) {
		
		var win = button.up('window'),
	    form = win.down('form'),
	    record = form.getRecord(),
	    values = form.getValues();

	    record.set(values);
	    win.close();
		
	  	// synchronize the store after editing the record
	    this.getFournisseursStore().sync();
    },
    createFournisseur: function(button) {
		
		var win = button.up('window');
	    form = win.down('form');
	    record = form.getRecord(),
	    values = form.getValues();


 		var tyf_nom_value = form.getForm().findField("tyf_id").getRawValue();

	    var fournisseurInstance = Ext.create('CT.model.Fournisseur', {

		    fou_id : -1,
		    fou_nom : values['fou_nom'],
		    fou_adresse : values['fou_adresse'],
		    fou_mail : values['fou_mail'],
		    fou_tel : values['fou_tel'],
		    fou_fax : values['fou_fax'],
		    tyf_id : values['tyf_id'],
		    tyf_nom : tyf_nom_value
		});

	    this.getFournisseursStore().add(fournisseurInstance);
	    win.close();
		
	  	// synchronize the store after editing the record
	    this.getFournisseursStore().sync();
    }
});