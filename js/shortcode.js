( function() {
	"use strict";

	var morepress_shortcode = function( editor, url ) {
		editor.addButton('morepress_shortcode', function() {
			var values = [];
			for (var i = 0; i < list_morepress_shortcodes.length; i++) {
				var _id = list_morepress_shortcodes[i];
				values.push({text: _id, value: _id});
			}

			return {
				type: 'listbox',
				text: 'Shortcodes',
				label: 'Choisir :',
				fixedWidth: true,
				onselect: function(e) {
					if (e) {
						var obj_json_shortcode = morepress_shortcodes[e.control.settings.value]
                           , selected = tinyMCE.activeEditor.selection.getContent();

						if (obj_json_shortcode['is_immediat']) {
                            var output = '['+ obj_json_shortcode['name']+']';
                            if(selected) {
                                output += selected+'[/'+obj_json_shortcode['name']+']';
                            }
							editor.insertContent(output);
						} else {
							editor.windowManager.open( {
								title: 'Shortcode : '+ e.control.settings.value,
								body: {
									type: 'form',
									items: obj_json_shortcode['fields']
						        },
								onsubmit: function( e ) {
									var output = '['+ obj_json_shortcode['name'];

									for (var i = 0; i < obj_json_shortcode['fields'].length; i++) {
										if (typeof obj_json_shortcode['fields'][i]['name'] !== 'undefined') {
											var name = obj_json_shortcode['fields'][i]['name']
                                              , val = e.data[name];

											output += ' '+name+'="'+val+'"';
										}
									}

                                    output += ']';
                                    if(selected) {
                                        output += selected+'[/'+obj_json_shortcode['name']+']';
                                    }
									editor.insertContent( output );
								}
							});
						}
					}
					return false;
				},
				values: values
			};
		});
	};
	tinymce.PluginManager.add( 'morepress_shortcode', morepress_shortcode );

} )();
