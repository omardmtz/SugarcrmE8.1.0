/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
 /**
 * @class
 * @name jQuery
 * @exports $ as jQuery
 */

(function( $ ) {
   /**
   * Creates a method object. This should be invoked as a method rather than constructed using new.
   * @class methods
   */
    var div = null;
    var methods = {
        /**
        * init method, the main methods for actions whit th tree
        * @constructs
        */
        init : function( options ) {
            
            var settings = {
                container: $(this),
                id        :'root'
            };
            return this.each(function(){
                if(options){
                    settings = $.extend(settings,options);
                }
                //HERE would be the code
                 var div =settings.container;
                //<div class="content_tree"></div>;
                div.append ($('<div>').addClass("content_tree"));
                //<ul id="root" class="menu"></ul>;
                var root = $('<ul>').attr({'class': 'tree-menu', id: settings.id});
                $('.content_tree').append(root);
                var tree=settings.items;
                if (typeof tree != 'undefined' && tree !== null)
                    createTree(root,tree);
                
                if (!options.collapsed) {
                    $('#'+settings.id+' ul').each(function() {
                        $(this).css("display", "none");
                    });
                }
                $('#'+settings.id+' .category').click(function() {
                    var childid = "#" + $(this).attr("childid");
			
                    if ($(childid).css("display") == "none") {
                        $(childid).css("display", "block");
                    } else {
                        $(childid).css("display", "none");
                    }
                    if ($(this).hasClass("tree_cat_close")) {
                        $(this).removeClass("tree_cat_close").addClass("tree_cat_open");
                    }	else {
                        $(this).removeClass("tree_cat_open").addClass("tree_cat_close");
                    }
                });
                $('.treechild').hover(
                    function(){
                        if ($(this).attr("status")=="unmarked"){
                            var id="#" + $(this).attr("id");
                            $(id).css("background","#EFF5FB");
                        }
                    },
                    function(){
                        if ($(this).attr("status")=="unmarked"){
                            var id="#" + $(this).attr("id");
                            $(id).css("background","#fff");
                        }
				
                    }
                    );

                $(".details").click(function(){
                    var id="#" + $(this).attr("desc");
                    $(".treechild").attr("status","unmarked");
                    $(".treechild").css("background","#fff");
			
                    $(id).css("background","#CEE3F6");
                    $(id).attr("status","marked");
                    var oShape = {};
                    if ($(this).attr("uid") !== undefined)
                        oShape.uid=$(this).attr("uid");
                    if ($(this).attr("name") !== undefined)
                        oShape.name=$(this).attr("name");
                    if ($(this).attr("type") !== undefined)
                        oShape.type=$(this).attr("type");
                    if (typeof settings.select !== 'undefined' && settings.select!== null) {
                        settings.select.call(this,oShape);
                    }
                });
                $('.treechild > a ').css({'text-decoration':'none'}); //clean a atribute decoratios
            });
        },
        
        
       
        /**
        * sample method
        * @param {String} a
        * @param {Object} b
        * @methodOf jQuery#
        * @name jQuery#example
        */
        example : function(a , b){}

    };
    /**
     *pmtree  method
     * @param {Object} method
    */
    $.fn.pmtree = function( method ) {
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exsts!' );
        }
        return true;
    };
    
     /**
        * createTree method
        * @param {Object} root
        * @param {Object} tree
        * @return append the html new code added to #root div
        */
        function createTree (root, tree) {
            var html='';
            for (var i=0; i< tree.length; i++){
               var li = $('<li>');
               html = $('<div>').addClass('treechild').attr({id: i, status:'unmarked'}).css({'cursor':'pointer', 'text-decoration':'none'});
               html.append($('<a>').addClass('tree_cat_close category').attr({childid:'c_'+i}).html('&nbsp;&nbsp;&nbsp'));
               // html  =  '<li>	<div class="treechild" id="'+i+'" status="unmarked"> <a childid = "c_'+i+'" class="tree_cat_close category">&nbsp;&nbsp;&nbsp;</a>';
               
                //alert(tree[i].icon);
                if (typeof tree[i].icon !== 'undefined' && tree[i].icon !== null) {
                    //html += '<a desc="'+i+'" class="details" name="'+tree[i].name+'" uid="'+tree[i].id+'" type="'+tree[i].type+'"><i class="'+tree[i].icon+'"></i> '+tree[i].name+'</a>';
                     html.append( '<a desc="'+i+'" class="details" name="'+tree[i].name+'" uid="'+tree[i].id+'" type="'+tree[i].type+'"><i class="'+tree[i].icon+'"></i> '+tree[i].name+'</a>');
                }
                else
                    //html +=  '<a desc="'+i+'" class="details" name="'+tree[i].name+'" uid="'+tree[i].id+'" type="'+tree[i].type+'"><i class="tree-folder-open"></i> '+tree[i].name+'</a>';
                    html.append( '<a desc="'+i+'" class="details" name="'+tree[i].name+'" uid="'+tree[i].id+'" type="'+tree[i].type+'"><i class="tree-folder-open"></i> '+tree[i].name+'</a>');
               // html += '</div>'
                //html += '</li>'
                li.append(html);
               // $('#'+settings.id).append(li);
                if (typeof tree[i].items != "undefined" &&  tree[i].items !== null )	{
                   //html +=	methods.addNodes(tree[i].items,'c_'+i);
                   li.append(addNodes(tree[i].items,'c_'+i));
                }
                
                root.append(li);
                //$('#'+settings.id).append(html);
            }
        }
        /**
        * addNodes method
        * @param {object} node
        * @param {string} childid
        * @return {string} html the added nodes
        */
        function addNodes(node,childid) {
            var html = '';
            html += '<ul id="'+childid+'">' ;
            for (var i=0; i<node.length; i++){
                //alert(node[i].name)
                html += '<li>';
                if (typeof node[i].items != "undefined" &&  node[i].items !== null )	{
                    html += '<div class="treechild" id="'+childid+'_'+i+'" status="unmarked" style="cursor:pointer; text-decoration:none;"><a childid = "c_'+childid+'_'+i+'"+ class="tree_cat_close category">&nbsp;&nbsp;&nbsp;</a>'; 
                    if (node[i].icon != 'undefined' && node[i].icon !== null)
						html +='<a desc="'+childid+'_'+i+'" class="details" name="'+node[i].name+'" uid="'+node[i].id+'" type="'+node[i].type+'"><i class="'+node[i].icon+'"></i> '+node[i].name+'</a>';
                    else
                        html +='<a desc="'+childid+'_'+i+'" class="details" name="'+node[i].name+'" uid="'+node[i].id+'" type="'+node[i].type+'"><i class="tree-folder-open"></i> '+node[i].name+'</a>';
                    html += '</div>';
                    html +=	addNodes(node[i].items,'c_'+childid+'_'+i);
                } else {
                    html += '<div class="treechild" id="'+childid+'_'+i+'" status="unmarked" style="cursor:pointer; text-decoration:none;"><a class="product">&nbsp;&nbsp;&nbsp;</a>';
                    if (typeof node[i].icon != 'undefined' && node[i].icon !== null)
                        html +='<a desc="'+childid+'_'+i+'" class="details" name="'+node[i].name+'" uid="'+node[i].id+'" type="'+node[i].type+'"><i class="'+node[i].icon+'"></i> '+node[i].name+'</a>';
                    else
                        html +='<a desc="'+childid+'_'+i+'" class="details" name="'+node[i].name+'" uid="'+node[i].id+'" type="'+node[i].type+'"><i class="tree-folder-open"></i> '+node[i].name+'</a>';
                    //html +=	addNodes(node[i].items,'c_'+childid+'_'+i);
                    html += '</div>';
                }
				//html += '<a>'+node[i].name+'</a>';
                html += '</li>';
            }
            html += '</ul>';
            return html;
        }
})( jQuery );
